<?php

class Ikantam_Lib_Installer
{
    static $_factory;

    function __construct(){

    }


    public function factory($mod){
        if(!isset(self::$_factory[$mod])){
            $class = 'Ikantam_Lib_Installer_' . $mod;
            self::$_factory[$mod] = new $class();
        }
        return self::$_factory[$mod];
    }




}
