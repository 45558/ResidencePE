<?php

namespace ResidencePE;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\inventory\PlayerInventory;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

class SelectionManager implements Listener{
    
    public $plugin;
    public $cfg;
    public $cfgman;
    public $loc1;
    public $loc2;
    
    public function __construct(ResidencePE $plugin){
        $this->plugin = $plugin;
        $this->cfg = $plugin->cfg;
        $this->selectors = $plugin->selectors;
        $this->cfgman = new ConfigManager($plugin);
    }
    
    public function onTouch(PlayerInteractEvent $e){
        $p = $e->getPlayer();
        $b = $e->getBlock();
        $tool = $this->cfgman->getSelectionToolId();
        if($p->getInventory()->getItemInHand()->getId() === $tool){
            $this->loc2[strtolower($p->getName())] = "$b->x:$b->y:$b->z";
            $p->sendMessage(TextFormat::GREEN.str_replace("%1", "2.", $this->plugin->getMessage("SelectPoint")).TextFormat::RED."($b->x, $b->y, $b->z)".TextFormat::GREEN."!");
            $e->setCancelled();
        }
    }
    
    public function onBlockBreak(BlockBreakEvent $e){
        $p = $e->getPlayer();
        $b = $e->getBlock();
        $tool = $this->cfg->getNested("Global.SelectionToolId");
        if($p->getInventory()->getItemInHand()->getId() === $tool){
            $this->loc1[strtolower($p->getName())] = "$b->x:$b->y:$b->z";
            $p->sendMessage(TextFormat::GREEN.str_replace("%1", "1.", $this->plugin->getMessage("SelectPoint")).TextFormat::RED."($b->x, $b->y, $b->z)".TextFormat::GREEN."!");
            $e->setCancelled();
        }
    }
}