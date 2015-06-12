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
}
