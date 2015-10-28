<?php

namespace ResidencePE;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class EventListener implements Listener{

    private $plugin;

    public function __construct(ResidencePE $plugin){
        $this->plugin = $plugin;
    }

    public function onBlockBreak(BlockBreakEvent $e){
        $p = $e->getPlayer();
    }
}
