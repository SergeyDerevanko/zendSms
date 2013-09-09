<?php

class Ikantam_Lib_Installer_Db
{
    private $_templatesPath;

    function __construct(){
        $this->setPath($this->getAppPath());
        $this->_templatesPath = $this->getPath() . '/../library/Ikantam/Lib/Installer/templates';
        return $this;
    }



    public function getConfig($name = 'default'){
        if(!Ikantam_Model::isInit())
            $this->createFileConfig($name);

        return Ikantam_Model::getConfig($name);
    }


    public function setConfig($adapter, $host, $username, $password, $dbname, $nameConf = 'default'){
        $file = new Ikantam_Lib_Explorer_File($this->getPath() . '/configs/db.xml');

        $tmp = $file->ready();
        $tmp = preg_replace("#\<" . $nameConf . ">(.*)</" . $nameConf . ">#is", '', $tmp);


        $block = $this->createXmlBlock($adapter, $host, $username, $password, $dbname, $nameConf);
        $tmp = str_replace('<!-- insert -->', $block, $tmp);
        $file->put($tmp);
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


    public function createXmlBlock($adapter = 'pdo_mysql', $host = 'localhost', $username = '', $password = '', $dbname = '', $nameConf = 'default'){
        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\dBconfig');
        $tmp = $tmp->ready();

        $tmp = str_replace('{adapter}', $adapter, $tmp);
        $tmp = str_replace('{host}', $host, $tmp);
        $tmp = str_replace('{username}', $username, $tmp);
        $tmp = str_replace('{password}', $password, $tmp);
        $tmp = str_replace('{dbname}', $dbname, $tmp);
        $tmp = str_replace('{nameConf}', $nameConf, $tmp);

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
