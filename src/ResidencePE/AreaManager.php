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
        if($this->resExist($rname)){
            $p->sendMessage(TextFormat::RED.str_replace("%1", TextFormat::YELLOW."$rname".TextFormat::RED, $this->plugin->getMessage("ResidenceAlreadyExist")));
            return;
        }
        $res = $this->getResFile($rname);
        if($res->getNested("Residences.$rname") != null){
            $p->sendMessage(TextFormat::RED.str_replace("%1", TextFormat::YELLOW.$rname.TextFormat::RED, $this->plugin->getMessage("ResidenceAlreadyExists")));
            return;
        }
        $res->setNested("LeaveMessage", $this->groups->getLeaveMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("EnterMessage", $this->group->getEnterMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("Permissions.PlayerFlags.".strtolower($p->getName()).".container", "true");
        $res->setNested("Permissions.PlayerFlags.".strtolower($p->getName()).".ignite", "true");
        $res->setNested("Permissions.PlayerFlags.".strtolower($p->getName()).".move", "true");
        $res->setNested("Permissions.PlayerFlags.".strtolower($p->getName()).".build", "true");
        $res->setNested("Permissions.PlayerFlags.".strtolower($p->getName()).".use", "true");
        $res->setNested("Permissions.GroupFlags", "");
        $res->setNested("Permissions.AreaFlags.container", "false");
        $res->setNested("Permissions.AreaFlags.ignite", "false");
        $res->setNested("Permissions.AreaFlags.build", "false");
        $res->setNested("Permissions.AreaFlags.firespread", "false");
        $res->setNested("Permissions.AreaFlags.use", "false");
        $res->setNested("Permissions.AreaFlags.pvp", "false");
        $res->setNested("Permissions.AreaFlags.tnt", "false");
        $res->setNested("Permissions.AreaFlags.flow", "false");
        $res->set("OwnerUUID", $p->getUniqueId());
        $res->set("OwnerName", strtolower($p->getName()));
        $res->setNested("Area.X1", $loc1->x);
        $res->setNested("Area.Y1", $loc1->y);
        $res->setNested("Area.Z1", $loc1->z);
        $res->setNested("Area.X2", $loc2->x);
        $res->setNested("Area.Y2", $loc2->y);
        $res->setNested("Area.Z2", $loc2->z);
        $res->setNested("Area.Level", $level);
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
    
    public function resExist($res){
        if(is_link($this->plugin->getDataFolder()."residences/".substr($res, 0, 1)."/$res.yml")){
            return true;
        }
        return false;
    }
    
    public function getResFile($rname){
        if($this->resExist($rname)){
            return new Config($this->plugin->getDataFolder()."residences/".substr($rname, 0, 1)."/$rname.yml");
        }
        return null;
    }
    
    public function getPlayerFile(Player $p){
        if(is_link($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml")){
            return new Config($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml", Config::YAML);
        }
    }
}