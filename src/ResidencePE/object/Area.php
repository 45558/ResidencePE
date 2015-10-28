<?php

namespace ResidencePE\object;

use pocketmine\math\AxisAlignedBB;

class Area{

    private $name;

    private $owner;

    private $flags = [];

    private $playersFlags = [];

    private $boundingBox;

    private $enterMessage;
    private $leaveMessage;

    public function __construct(array $data, $level){

    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEnterMessage(){
        return $this->enterMessage;
    }

    /**
     * @param string $message
     */
    public function setEnterMessage($message){
        $this->enterMessage = $message;
    }

    /**
     * @return string
     */
    public function getLeaveMessage(){
        return $this->enterMessage;
    }

    /**
     * @param string $message
     */
    public function setLeaveMessage($message){
        $this->leaveMessage = $message;
    }

    /**
     * @param string $flag
     * @return bool
     */
    public function getFlag($flag){
        if(!isset($this->flags[$flag])){
            return false;
        }
        return $this->flags[$flag];
    }

    /**
     * @param string $flag
     * @return bool
     */
    public function getPlayerFlag($flag){
        if(!isset($this->playersFlags[$flag])){
            return false;
        }
        return $this->playersFlags[$flag];
    }

    /**
     * @return AxisAlignedBB
     */
    public function getBoundingBox(){
        return $this->boundingBox;
    }

    /**
     * @return string
     */
    public function getOwner(){
        return $this->owner;
    }
}