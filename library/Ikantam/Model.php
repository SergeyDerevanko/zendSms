<?php

class Ikantam_Model
{
    private static $_db;

    public static function getConnect(){
        if (!self::$_db) {
            $cfg = self::getConfig();

            $config = array(
                'host'     => $cfg->host,
                'dbname' => $cfg->dbname,
                'username' => $cfg->username,
                'password' => $cfg->password,

            );

            self::$_db = Zend_Db::factory($cfg->adapter, $config);
        }
        return self::$_db;
    }


    public static function getConfig($type = 'default'){
        return new Zend_Config_Xml(APPLICATION_PATH.'/configs/db.xml', $type);
    }


    public static function isInit(){
        try {
            new Zend_Config_Xml(APPLICATION_PATH.'/configs/db.xml');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function isConfig($name = 'default'){
        try {
            self::getConfig($name);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
