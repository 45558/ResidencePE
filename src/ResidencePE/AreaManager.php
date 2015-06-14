<?php

namespace ResidencePE;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;

class AreaManager{
    
    public $plugin;
    public $groups;
    
    public function __construct(ResidencePE $plugin) {
        $this->plugin = $plugin;
        $this->groups = new GroupsManager($plugin);
    }
    
    public function isResidenceOwner(Player $player, $rname){
        if($this->resExist($rname)){
            $res = new Config($this->getResFile($rname));
            if($res->getNested("Owner") == strtolower($player->getName())){
                return true;
            }
            return false;
        }
        return null;
    }
    
    public function createResidence(Player $p, $rname, $loc1, $loc2,Level $level, $admin){
        $group = $this->groups->getPlayerGroup($p);
        if(count($this->getPlayerResidences($p)) >= $this->group->getMaxResidencesPerGroup($group)){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceTooMany"));
            return;
        }
        if($this->checkCollision($loc1, $loc2, $level)){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceColide"));
            return;
        }
        if(!$this->residenceLimits($p, $loc1, $loc2)){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceTooBig"));
            return;
        }
        if($this->groups->getGroupMainPermission($group, "CanCreate") != "true"){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("NoPermission"));
            return;
        }
        $res;
        if(!file_exists($this->plugin->getDataFolder()."residences/")){
            @mkdir($this->plugin->getDataFolder()."residences/", 0777, true);
        }
        $res = $this->getResFile($rname, $level);
        if($this->resExist($rname)){
            $p->sendMessage(TextFormat::RED.str_replace("%1", TextFormat::YELLOW.$rname.TextFormat::RED, $this->plugin->getMessage("ResidenceAlreadyExists")));
            return;
        }
        $res->setNested("Residences.$rname.LeaveMessage", $this->groups->getLeaveMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("Residences.$rname.EnterMessage", $this->group->getEnterMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("Residences.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".container", "true");
        $res->setNested("Residences.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".ignite", "true");
        $res->setNested("Residences.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".move", "true");
        $res->setNested("Residences.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".build", "true");
        $res->setNested("Residences.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".use", "true");
        $res->setNested("Residences.$rname.Permissions.GroupFlags", "");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.container", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.ignite", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.build", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.firespread", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.use", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.pvp", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.tnt", "false");
        $res->setNested("Residences.$rname.Permissions.AreaFlags.flow", "false");
        $res->set("Residences.$rname.OwnerUUID", $p->getUniqueId());
        $res->set("Residences.$rname.OwnerName", strtolower($p->getName()));
        $res->setNested("Residences.$rname.Area.X1", $loc1->x);
        $res->setNested("Residences.$rname.Area.Y1", $loc1->y);
        $res->setNested("Residences.$rname.Area.Z1", $loc1->z);
        $res->setNested("Residences.$rname.Area.X2", $loc2->x);
        $res->setNested("Residences.$rname.Area.Y2", $loc2->y);
        $res->setNested("Residences.$rname.Area.Z2", $loc2->z);
        $res->save();
        $res->reload();
    }
    
    public function removeResidence(Player $p, $rname, $admin){
        if($this->isResidenceOwner($p, $rname) || $admin = true){
            
        }
    }
    
    public function getPlayerResidences(Player $p){
        return $this->getPlayerFile($p)->getNested("Residences");
    }
    
    public function checkCollision($loc1, $loc2, Level $level){
        $axis = new AxisAlignedBB(min($loc1->x, $loc2->x), min($loc1->y, $loc2->y), min($loc1->z, $loc2->z), max($loc1->x, $loc2->x), max($loc1->y, $loc2->y), max($loc1->z, $loc2->z));
        foreach (glob($this->plugin->getDataFolder()."residences/*/*.yml") as $file) {
            $res = $this->getResFile($file);
            $r = $res->getAll();
            if($axis->intersectsWith(new AxisAlignedBB(min($r["Area"]["X1"], $r["Area"]["X2"]), min($r["Area"]["Y1"], $r["Area"]["Y2"]), min($r["Area"]["Z1"], $r["Area"]["Z2"]), max($r["Area"]["X1"], $r["Area"]["X2"]), max($r["Area"]["Y1"], $r["Area"]["Y2"]), max($r["Area"]["Z1"], $r["Area"]["Z2"])))){
                return true;
            }
        }
        return false;
    }
    
    public function residenceLimits(Player $player, $loc1, $loc2){
        $group = $this->groups->getPlayerGroup($player);
        if($this->groups->getGroupMainPermission($group, "MaxEastWest") >= (max($loc1->x, $loc2->x) - min($loc1->x, $loc2->x)) && $this->groups->getGroupMainPermission($group, "MaxNorthSouth") >= (max($loc1->z, $loc2->z) - min($loc1->z, $loc2->z)) && $this->groups->getGroupMainPermission($group, "MaxUpDown") >= (max($loc1->y, $loc2->y) - min($loc1->y, $loc2->y)) && $this->groups->getGroupMainPermission($group, "MinHeight") <= min($loc1->y, $loc2->y) && $this->groups->getGroupMainPermission($group, "MaxHeight") >= max($loc1->y, $loc2->y)){
            return true;
        }
        return false;
    }
    
    public function resExist($rname){
        foreach(glob($this->plugin->getDataFolder()."residences/res_*.yml") as $file){
            $res = new Config($file, Config::YAML);
            if($res->getNested("Residences.$rname") !== null){
                return $file;
            }
        }
        return false;
    }
    
    public function getResFile($rname, Level $level){
        if($level != null){
            return new Config($this->plugin->getDataFolder()."residences/res_{$level->getName()}.yml");
        }
        if($this->resExist($rname) != false){
            return new Config($this->resExist($rname), Config::YAML);
        }
        return null;
    }
    
    public function getPlayerFile(Player $p){
        if(is_link($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml")){
            return new Config($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml", Config::YAML);
        }
    }
}