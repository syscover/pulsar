<?php

namespace Pulsar\Support\Facades;

class Config {
    
    public static function get($var){
        $config = (include '../config/getfile.php');
        return $config[$var];
    }
}