<?php
namespace ResidencePE;

class ConfigManager {
    protected $enableEconomy;
    protected $infoToolId;
    protected $selectionToolId;
    protected $autoSaveInt;
    protected $language;
    
    public $cfg;
    public $plugin;
    
    public function __construct(ResidencePE $plugin) {
        $this->plugin = $plugin;
        $this->cfg = $plugin->cfg;
    }
    
    public function getSelectionToolId(){
        return $this->cfg->getNested("Global.SelectionToolId");
    }
    
    public function getInfoToolId(){
        return $this->cfg->getNested("Global.InfoToolId");
    }
    
    public function getAutoSaveInt(){
        return $this->cfg->getNested("Global.SaveInterval");
    }
    
    public function getFlagPermission($key){
        $content = $this->cfg->getAll()["Global"]["FlagPermission"][$key];
        if(isset($content)){
            return $content;
        }
        return null;
    }
    
    public function getResDefault($key){
        $content = $this->cfg->getAll()["Global"]["ResidenceDefault"][$key];
        if(isset($content)){
            return $content;
        }
        return null;
    }
    
    public function getCreatorDefault($key){
        $content = $this->cfg->getAll()["Global"]["CreatorDefault"][$key];
        if(isset($content)){
            return $content;
        }
        return null;
    }
}
