<?php
namespace ResidencePE;

class ConfigManager {
    protected $enableEconomy;
    protected $allowEmptyResidences;
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
    
    public function getEmptyResidences(){
        return $this->cfg->getNested("Global.AllowEmptyResidences");
    }
    
    public function getSelectionToolId(){
        return $this->cfg->getNested("Global.SelectionToolId");
    }
    
    public function getSelectionToolId(){
        return $this->cfg->getNested("Global.SelectionToolId");
    }
    
    public function getSelectionToolId(){
        return $this->cfg->getNested("Global.SelectionToolId");
    }
}
