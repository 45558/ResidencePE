<?php

namespace ResidencePE;

class GroupsManager{
    
    public $cfg;
    public $plugin;
    
    public function __construct(ResidencePE $plugin) {
        $this->plugin = $plugin;
        $this->cfg = $plugin->cfg;
    }
    
    public function getGroupMainPermission($group, $key){
        return $this->cfg->getNested("Groups.$group.Residence.$key");
    }
    
    public function getGroupCreatordefault($group, $key){
        return $this->cfg->getNested("Groups.$group.Flags.CreatorDefault.$key");
    }
    
    public function getGroupFlagPermission($group, $key){
        return $this->cfg->getNested("Groups.$group.Flags.Permissions.$key");
    }
    
    public function getGroupResDefault($group, $perm){
        return $this->cfg->getNested("Groups.$group.Flags.ResidenceDefault.$key");
    }
    
    public function getLeaveMessage($group){
        return $this->cfg->getNested("Groups.$group.Messaging.DefaultLeave.$key");
    }
    
    public function getEnterMessage($group){
        return $this->cfg->getNested("Groups.$group.Messaging.DefaultEnter.$key");
    }
    
    public function getPlayerGroup(Player $p){
        if(is_link($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml")){
            $file = new Config($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()).".yml", Config::YAML);
            return $file->get("Group");
        }
    }
    
    public function getDefaultGroup(){
        if($this->cfg->getNested("Groups.Settings.DefaultGroup") !== null){
            return $this->cfg->getNested("Groups.Settings.DefaultGroup");
        }
        $this->plugin->getLogger()->critical(TextFormat::DARK_RED."Default group doesn´t exist");
        return "";
    }
}