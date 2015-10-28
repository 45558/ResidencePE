<?php

namespace ResidencePE;


use pocketmine\utils\Config;

class Messages{

    private static $messages = [];

    public function init(Config $cfg){
        self::$messages = $cfg->getAll();
    }

    public static function getMsg($key, $search, $replace){
        if(!isset(self::$messages[$key])){
            return null;
        }
        return str_replace(self::$messages[$key]);
    }

}