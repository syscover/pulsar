<?php

namespace Pulsar\Support\Facades;

use Pulsar\Support\Facades\File;

class Input {
    
    public static function get($var, $default = null){
        if(isset($_REQUEST[$var]))
        {
            return $_REQUEST[$var];
        }
        
        return $default;
    }

    public static function has($var){
        return isset($_REQUEST[$var]);
    }

    public static function hasFile($var){
        return isset($_FILES[$var]);
    }
    
    public static function file($var){
        return new File($_FILES[$var]);
    }
}