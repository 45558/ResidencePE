<?php

namespace ResidencePE;

class GroupsManager{
    
    public $cfg;
    public $plugin;
    public $groups;
    
    public function __construct(ResidencePE $plugin) {
        $this->plugin = $plugin;
        $this->cfg = $plugin->cfg;
    }
    
    public function getMaxResidencesPerGroup($group){
        return $this->cfg->getNested("Groups.$group.Residence.MaxResidences");
    }
    
    public function hasGroupPermission($group, $perm){
        
    }
    
    public function getLeaveMessage($group){
        
    }
    
    public function getEnterMessage($group){
        
    }
    
    public function getPlayerGroup($player){
        
    }
}