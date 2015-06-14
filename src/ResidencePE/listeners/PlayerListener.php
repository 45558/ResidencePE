<?php

namespace ResidencePE\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\Player;
use pocketmine\utils\Config;

class PlayerListener implements Listener{
    
    public $plugin;
    
    public function __construct(ResidencePE $plugin){
        $this->plugin = $plugin;
    }
    
    public function onLogin(PlayerLoginEvent $e){
        $p = $e->getPlayer();
        if(!file_exists($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/")){
            @mkdir($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/");
        }
        if(!is_link($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()))){
            $file = new Config($this->plugin->getDataFolder()."players/".strtolower(substr($p->getName(), 0, 1))."/".strtolower($p->getName()), Config::YAML);
            $file->set(strtolower($p->getName()).".Residences", "");
            $file->set(strtolower($p->getName()).".Group", "");
        }
    }
}