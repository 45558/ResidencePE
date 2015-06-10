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
    public $selectors;
    
    public function __construct(ResidencePE $plugin){
        $this->plugin = $plugin;
        $this->cfg = $plugin->cfg;
        $this->selectors = $plugin->selectors;
    }
    
    public function onTouch(PlayerInteractEvent $e){
        $p = $e->getPlayer();
        $b = $e->getBlock();
        $tool = $this->cfg->getNested("Global.SelectionToolId");
        if($p->getInventory()->getItemInHand()->getId() === $tool){
            $this->selectors[strtolower($p->getName())] = ["pos1" => "$b->x:$b->y:$b->z"];
            $p->sendMessage(TextFormat::GREEN.str_replace("%1", "2.", $this->plugin->getMessage("SelectPoint")).TextFormat::RED."($b->x, $b->y, $b->z)".TextFormat::GREEN."!");
            $e->setCancelled();
        }
    }
    
    public function onBlockBreak(BlockBreakEvent $e){
        $p = $e->getPlayer();
        $b = $e->getBlock();
        $tool = $this->cfg->getNested("Global.SelectionToolId");
        if($p->getInventory()->getItemInHand()->getId() === $tool){
            $this->selectors[strtolower($p->getName())] = ["pos2" => "$b->x:$b->y:$b->z"];
            $p->sendMessage(TextFormat::GREEN.str_replace("%1", "1.", $this->plugin->getMessage("SelectPoint")).TextFormat::RED."($b->x, $b->y, $b->z)".TextFormat::GREEN."!");
            $e->setCancelled();
        }
    }
}