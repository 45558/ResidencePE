<?php

namespace ResidencePE;

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
            $player->sendMessage();
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
        $res->setNested("Residences.$rname.");
    }
    
    public function getPlayerResidences(Player $p){
        
    }
    
}