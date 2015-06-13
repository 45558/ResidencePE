<?php

namespace ResidencePE;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\Player;

class AreaManager{
    
    public $plugin;
    public $groups;
    
    public function __construct(ResidencePE $plugin) {
        $this->plugin = $plugin;
        $this->groups = new GroupsManager($plugin);
    }
    
    public function isResidenceAdmin(Player $player){
        
    }
    
    public function createResidence(Player $p, $rname, $loc1, $loc2,Level $level){
        if($this->getPlayerResidences($p) >= $this->group->getMaxResidencesPerGroup($this->groups->getPlayerGroup($p))){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceTooMany"));
            return;
        }
        elseif($this->checkCollision($loc1, $loc2, $level)){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceColide"));
            return;
        }
        elseif(!$this->residenceLimits($loc1, $loc2)){
            $p->sendMessage(TextFormat::RED.$this->plugin->getMessage("ResidenceTooBig"));
            return;
        }
        $res;
        if(!file_exists($this->plugin->getDataFolder()."save/")){
            @mkdir($this->plugin->getDataFolder()."save/", 0777, true);
        }
        if(!file_exists($this->plugin->getDataFolder()."save/worlds/")){
            @mkdir($this->plugin->getDataFolder()."save/worlds/", 0777, true);
        }
        $res = new Config($this->plugin->getDataFolder()."save/worlds/res_{$level->getName()}");
        if($res->getNested("Residences.$rname") != null){
            $p->sendMessage(TextFormat::RED.str_replace("%1", TextFormat::YELLOW.$rname.TextFormat::RED, $this->plugin->getMessage("ResidenceAlreadyExists")));
            return;
        }
        $res->setNested("Residences.$rname.LeaveMessage", $this->groups->getLeaveMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("Residence.$rname.EnterMessage", $this->group->getEnterMessage($this->groups->getPlayerGroup($p)));
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".container", "true");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".ignite", "true");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".move", "true");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".build", "true");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".use", "true");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".GroupFlags", "{}");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.container", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.ignite", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.build", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.firespread", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.use", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.pvp", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.tnt", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".AreaFlags.flow", "false");
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".OwnerUUID", $p->getUniqueId());
        $res->setNested("Residence.$rname.Permissions.PlayerFlags.".strtolower($p->getName()).".OwnerName", strtolower($p->getName()));
        $res->setNested("Residence.$rname.Area.X1", $loc1->x);
        $res->setNested("Residence.$rname.Area.Y1", $loc1->y);
        $res->setNested("Residence.$rname.Area.Z1", $loc1->z);
        $res->setNested("Residence.$rname.Area.X2", $loc2->x);
        $res->setNested("Residence.$rname.Area.Y2", $loc2->y);
        $res->setNested("Residence.$rname.Area.Z2", $loc2->z);
    }
    
    public function getPlayerResidences(Player $p){
        
    }
    
    public function checkCollision($loc1, $loc2, Level $level){
        $res = new Config($this->plugin->getDataFolder()."res_".$level->getName());
        $residences = $res->getAll();
        $y = min($loc1->y, $loc2->y);
        $z = min($loc1->y, $loc2->y);
            for($x = min($loc1->x, $loc2->x); $x != max($loc1->x, $loc2->x); $x++){
                foreach($residences["Residences"] as $r){
                    if((min($r["Area"]["X1"], $r["Area"]["X2"]) <= $x) && (max($r["Area"]["X1"], $r["Area"]["X2"]) >= $x) && (min($r["Area"]["Y1"], $r["Area"]["Y2"]) <= $y) && (max($r["Area"]["Y1"], $r["Area"]["Y2"]) >= $y) && (min($r["Area"]["Z1"], $r["Area"]["Z2"]) <= $z) && (max($r["Area"]["X1"], $r["Area"]["X2"]) >= $z)){
                        return true;
                    }
                }
                if($y == max($loc1->y, $loc2->y) && $x == max($loc1->x, $loc2->x) && $z == max($loc1->z, $loc2->z)){
                    break;
                }
                if($x == max($loc1->x, $loc2->x)){
                    if($z == max($loc1->z, $loc2->z)){
                        $y++;
                        $x = min($loc1->x, $loc2->x);
                        $z = min($loc1->z, $loc2->z);
                    }
                    else{
                        $x = min($loc1->x, $loc2->x);
                        $z++;
                    }
                }
            }
    }
    
    public function residenceLimits($loc1, $loc2){
        
    }
    
    public function getContains($pos1, $pos2){
        
    }
}