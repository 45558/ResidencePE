<?php

namespace ResidencePE;

use MassiveEconomy\MassiveEconomyAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use PocketMoney\PocketMoney;
use ResidencePE\ConfigManager as Cfg;
use ResidencePE\object\Area;
use ResidencePE\object\Group;
use ResidencePE\object\ResidencePlayer;

class ResidencePE extends PluginBase{

    /** @var  Manager */
    private $manager;

    /** @var Area[] */
    private $areas = [];

    /** @var Group[] */
    private $groups = [];

    /** @var ResidencePlayer[] */
    private $players = [];

    /** @var  PocketMoney | EconomyAPI | MassiveEconomyAPI $economy */
    private $economy;

    private $economyProvider;

    public function onLoad(){

    }

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveResource("config.yml");
        $this->saveResource("languagefiles/Brazilian.yml");
        $this->saveResource("languagefiles/Chinese.yml");
        $this->saveResource("languagefiles/Czech.yml");
        $this->saveResource("languagefiles/English.yml");
        $this->saveResource("languagefiles/French.yml");
        $this->saveResource("languagefiles/German.yml");
        $this->saveResource("languagefiles/Hungarian.yml");
        $this->saveResource("languagefiles/Polish.yml");
        $this->saveResource("languagefiles/Spanish.yml");
        if(!is_dir($this->getDataFolder()."worlds/")){
            mkdir($this->getDataFolder()."worlds/");
        }
        $this->registerConfig();
        $this->registerGroups();
        $this->registerResidences();
        $this->registerEconomy();
        $this->manager = new Manager($this);
    }

    public function registerResidences(){
        $path = $this->getDataFolder()."worlds/";
        foreach(array_diff(scandir($path), [".", ".."]) as $dir){
            if(is_dir($path.$dir)){
                $level = $dir;
                foreach(glob($path.$dir."*.yml") as $file){
                    $cfg = new Config($file);
                    $data = $cfg->getAll();
                    if(count($data) < 8){
                        continue;
                    }
                    if(!(isset($data["Name"]) && isset($data["Owner"]) && isset($data["PlayerFlags"]) && isset($data["AreaFlags"]) && isset($data["EnterMessage"]) && isset($data["LeaveMessage"]))){
                        continue;
                    }
                    if(!(isset($data["Pos1"]["X"]) && is_numeric($data["Pos1"]["X"]) && isset($data["Pos1"]["Y"]) && is_numeric($data["Pos1"]["Z"]) && isset($data["Pos2"]["X"]) && is_numeric($data["Pos2"]["X"]) && isset($data["Pos2"]["Y"]) && is_numeric($data["Pos2"]["Y"]) && isset($data["Pos2"]["Z"]) && is_numeric($data["Pos1"]["X"]))){
                        continue;
                    }
                    $this->areas[$data["Name"]] = new Area($data, $level);
                }
            }
        }
    }

    public function registerGroups(){
        foreach(Cfg::get("Groups") as $group){

        }
    }

    public function registerConfig(){

    }

    public function registerEconomy(){
        if(($plugin  = $this->getServer()->getPluginManager()->getPlugin("PocketMoney")) instanceof Plugin && $plugin->isEnabled()){
            $this->economy = $plugin;
        }elseif(($plugin  = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")) instanceof Plugin && $plugin->isEnabled()){
            $this->economy = $plugin;
        }elseif(($plugin  = $this->getServer()->getPluginManager()->getPlugin("MassiveEconomy")) instanceof Plugin && $plugin->isEnabled()){
            $this->economy = $plugin;
        }
    }

    /**
     * @param string $groupName
     */
    public function getGroup($groupName){

    }

    /**
     * @param string $name
     */
    public function getResidencePlayer($name){

    }
}