<?php

namespace ResidencePE\object;


class Group{

    private $name;

    //residence
    private $canCreate;
    private $maxResidences;
    private $zMax;
    private $xMax;
    private $yMax;
    private $minHeight;
    private $maxHeight;
    private $canTeleport;
    private $unstuck;
    private $kick;
    private $selectCommand;

    //messaging
    private $canChange;
    private $defaultEnter;
    private $defaultLeave;

    //economy
    private $canBuy;

    public function __construct($name, $data){

    }


    public function getName(){
        return $this->name;
    }
}