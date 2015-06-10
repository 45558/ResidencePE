<?php

namespace ResidencePE;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

class ResidenceCommandListener implements CommandExecutor{
    
    public $plugin;
    public $manager;
    public $cfg;
    
    public function __construct(ResidencePE $plugin){
        $this->plugin = $plugin;
        $this->manager = new AreaManager($plugin);
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        if(strtolower($cmd->getName()) == "res" || strtolower($cmd->getName()) == "residence" || strtolower($cmd->getName()) == "resadmin"){
            if($sender instanceof Player){
                $resadmin = false;
                if($this->manager->isResidenceAdmin($sender) && strtolower($cmd->getName()) == "resadmin"){
                    $resadmin = true;
                }
                if(!$this->manager->isResidenceAdmin($sender) && strtolower($cmd->getName()) == "resadmin"){
                    $sender->sendMessage(TextFormat::RED.$this->plugin->getMessage("NotPermissions"));
                }
                else{
                    $this->commandRes($sender, $cmd, $args, $resadmin);
                }
            }
        }
    }
    
    public function commandRes(CommandSender $sender, Command $cmd, array $args, $resadmin){
        
    }
}