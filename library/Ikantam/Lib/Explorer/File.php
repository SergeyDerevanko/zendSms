<?php

class Ikantam_Lib_Explorer_File
{
    private $_path;
    private $_name;

    function __construct($path = null){
        $this->setPath($path);
        return $this;
    }


    public function setPath($path = ''){
        if(!empty($path)){
            $this->_path = str_replace('/', '\\', $path);
        }
        return $this;
    }


    public function getPath(){
        return $this->_path;
    }


    public function create($name){
        if( !file_exists($name)) {
            $fp = fopen($name, "w");
            fwrite($fp, "");
            fclose ($fp);
            $this->_path = $name;
        }
        return $this;
    }


    public function delete(){
        if( file_exists($this->_path)) {
            return unlink($this->_path);
        }
        return false;
    }


    public function ready(){
        return file_get_contents($this->getPath());
    }


    public function clear(){
        return $this->_put('');
    }


    public function write($string = ''){
        return $this->_put($string);
    }


    public function put($string = ''){
        $this->clear();
        return $this->_put($string);

    }


    private function _put($string = ''){
        return file_put_contents($this->_path, $string, LOCK_EX);
    }


    public function setName($name){
        $this->_name = $name;
        return $this;
    }


    public function getName(){
        return $this->_name;
    }
}
