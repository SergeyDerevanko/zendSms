<?php

class Application_Model_DbFactory{

	private static $_db;

    public static function getConnect(){
        if (!self::$_db) {
            $cfg = new Zend_Config_Xml(APPLICATION_PATH.'/configs/db.xml', 'default');

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
}
