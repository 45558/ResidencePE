<?php

namespace ResidencePE;

use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;

class ResidencePE extends PluginBase{
    public $selectors = [];
    public $msg;
    public $cfg;
    
    public function onEnable(){
        try {
            $this->saveDefaultConfig ();
            if (! file_exists ( $this->getDataFolder () )) {
            	@mkdir ( $this->getDataFolder (), 0777, true );
            	file_put_contents ( $this->getDataFolder () . "config.yml", $this->getResource ( "config.yml" ) );
                file_put_contents ( $this->getDataFolder ()."czech.yml", $this->getResource ( "czech.yml" ) );
            }
            $this->reloadConfig ();
            $this->getConfig ()->getAll ();			
	} catch ( \Exception $e ) {
            $this->getLogger ()->error ( $e->getMessage());
	}
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->msg = new Config($this->getDataFolder().$this->cfg->getNested("Global.Language").".yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new SelectionManager($this), $this);
        $this->getLogger()->info(TextFormat::GREEN."ResidencePE enabled!");
    }
    
    public function onDisable(){
        $this->getLogger()->info(TextFormat::RED."ResidencePE disabled");
    }
    
    public function getMessage($key){
        return var_dump($this->msg->get($key));
    }
}

