<?php

namespace ResidencePE;

use pocketmine\utils\Config;

class ConfigManager{

    /** @var  Config $cfg */
    private static $cfg;

    public function init(Config $cfg){
        self::$cfg = $cfg;
    }

    /**
     * @param $key
     * @return null | string
     */
    public static function get($key, $default = null){
        return self::$cfg->get($key, $default);
    }

    /**
     * @return array
     */
    public static function getAll(){
        return self::$cfg;
    }
}