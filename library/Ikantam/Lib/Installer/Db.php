<?php

class Ikantam_Lib_Installer_Db
{
    private $_templatesPath;

    function __construct(){
        $this->setPath($this->getAppPath());
        $this->_templatesPath = $this->getPath() . '/../library/Ikantam/Lib/Installer/templates';
        return $this;
    }


    public function testConnect(){
        try{
            Ikantam_Model::getConnect()->listTables();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return "Good Connection";
    }


    public function getConfig($name = 'default'){
        if(!Ikantam_Model::isInit())
            $this->createFileConfig($name);

        return Ikantam_Model::getConfig($name);
    }


    public function setConfig($adapter, $host, $username, $password, $dbname, $prefix, $nameConf = 'default'){
        $file = new Ikantam_Lib_Explorer_File($this->getPath() . '/configs/db.xml');

        $tmp = $file->ready();
        $tmp = preg_replace("#\<" . $nameConf . ">(.*)</" . $nameConf . ">#is", '', $tmp);


        $block = $this->createXmlBlock($adapter, $host, $username, $password, $dbname, $prefix, $nameConf);
        $tmp = str_replace('<!-- insert -->', $block, $tmp);
        $file->put($tmp);
    }


    public function sql($sql){
        $connect = Ikantam_Model::getConnect();
        $connect->query($sql);
    }


    public function mrg(){
        $prefix = Ikantam_Model::getPrefix();
        $sql = "
        CREATE TABLE IF NOT EXISTS `{$prefix}sys_option` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `type` text NOT NULL,
          `name` text NOT NULL,
          `value` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
        ";
        $this->sql($sql);
    }


    public function getAppPath(){
        return APPLICATION_PATH;
    }


    public function setPath($path = ''){
        if(!empty($path)){
            $this->_path = $path;
        }
        return $this;
    }


    public function getPath(){
        return $this->_path;
    }


    public function createXmlBlock($adapter = 'pdo_mysql', $host = 'localhost', $username = '', $password = '', $dbname = '', $prefix = '', $nameConf = 'default'){
        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\dBconfig');
        $tmp = $tmp->ready();

        $tmp = str_replace('{adapter}', $adapter, $tmp);
        $tmp = str_replace('{host}', $host, $tmp);
        $tmp = str_replace('{username}', $username, $tmp);
        $tmp = str_replace('{password}', $password, $tmp);
        $tmp = str_replace('{dbname}', $dbname, $tmp);
        $tmp = str_replace('{nameConf}', $nameConf, $tmp);
        $tmp = str_replace('{prefix}', $prefix, $tmp);
        return $tmp;
    }

    private function createFileConfig($name){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/configs');
        $xml = $this->createXmlBlock('pdo_mysql', 'localhost', '', '', '', $name);
        $dir->createFile('db.xml')->put("<?xml version='1.0'?>
<configdata>
    $xml
</configdata>");
    }

}
