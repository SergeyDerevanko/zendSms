<?php

class Ikantam_Lib_Explorer_Dir extends Ikantam_Collection
{
    private $_path;
    private $_name;


    function __construct($path = null){
        $this->setPath($path);
        return $this;
    }


    public function createFile($name = null){
        $name = $name ? $name : time() . '.tmp';
        $name = $this->getPath() . '\\' . $name;
        $file = new Ikantam_Lib_Explorer_File();
        $file->create($name);
        return $file;
    }


    public function createDir($name = null){
        $name = $name ? $name : time() . '_tmp';
        $name = $this->getPath() . '\\' . $name;
        $dir = new Ikantam_Lib_Explorer_Dir();
        $dir->create($name);
        return $dir;
    }




    public function create($name = null){
        mkdir($name);
        $this->_path = $name;
        return $this;
    }


    public function getList($type = null){
        $this->_list = new Ikantam_Lib_Explorer_Dir();
        if(file_exists($this->_path)){
            foreach(scandir($this->_path) as $_e) {
                $_path = $this->_path . '\\' . $_e;
                $_type = is_dir($_path) ? 'dir' : 'file';
                $pars = explode('\\', $_path);
                $name = $pars[count($pars) - 1];
                if(!$type || ($type == $_type && (($type == 'dir' && !in_array($name, array('.', '..')) || $type == 'file' )))){
                    $element = $_type == 'dir' ? new Ikantam_Lib_Explorer_Dir() : new Ikantam_Lib_Explorer_File();
                    $element->setPath($_path);

                    $element->setName($name);
                    $this->_list->addItem($element);
                }

            }
        }
        return $this->_list;
    }


    public function cdNew($cmd){
        $object = new Ikantam_Lib_Explorer_Dir($this->getPath());
        $object->cd($cmd);
        return $object;
    }


    public function cd($cmd = NULL){
        if($cmd){
            $cmd = str_replace('/', '\\', $cmd);
            $this->setPath($this->getPath() . "\\" . $cmd);
        }
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


    public function setName($name){
        $this->_name = $name;
        return $this;
    }


    public function getName(){
        return $this->_name;
    }

}
