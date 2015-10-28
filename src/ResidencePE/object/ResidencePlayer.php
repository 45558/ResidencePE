<?php

namespace ResidencePE\object;


use pocketmine\Player;

class ResidencePlayer{

    /** @var  Player $player */
    private $player;
    /** @var  Group $group */
    private $group;

    public function getPlayer(){
        return $this->player;
    }

    /**
     * @return Group
     */
    public function getGroup(){
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group){
        $this->group = $group;
    }
}