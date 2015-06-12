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
        elseif($this->isColide($loc1, $loc2)){
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
    
    public function isColide($loc1, $loc2){
        
    }
    
    public function residenceLimits($loc1, $loc2){
        
    }
}