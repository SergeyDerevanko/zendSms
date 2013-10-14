<?php
class Storage_Service_Local extends Storage_Service_Abstract
{
    protected $_type = 'local';
    protected $_path;

    public function __construct(array $config){
        if( !empty($config['path']) ) {
            $this->_path = APPLICATION_PATH . '/../public/'. $this->getPathForMap();
        } else {
            $this->_path = APPLICATION_PATH . '/../public/' . $this->getPathForMap();
        }
        parent::__construct($config);
    }



    public function getPath(){
        return $this->_path;
    }


    public function getPathForMap(){
        if(!empty($this->_config['path'])){
            return $this->_config['path'] . '/';
        } else {
            return 'storage_files/';
        }
    }


    public function store($filePath, $extension){
        $_filePath = $this->generate($extension);
        $path = $this->getPath() . $_filePath;

        try{
            $this->_mkdir(dirname($path));
            $this->_copy($filePath, $path);
            @chmod($path, 0777);
        } catch( Exception $e ){
            @unlink($path);
            throw $e;
        }
        return $_filePath;
    }


    public function copy(Storage_Model_File $model){
        $_filePath = $this->generate($model->getExtension());
        $path = $this->getPath() . $_filePath;

        try{
            $this->_mkdir(dirname($path));
            $this->_copy($this->map($model), $path);
            @chmod($path, 0777);
        } catch( Exception $e ){
            throw $e;
        }
        return $_filePath;
    }


    public function href(Storage_Model_File $model){
        return Ikantam_Lib_Url::getPublicUrl(rtrim($this->getPathForMap(), '/') . '/' . $model->getStoragePath());
    }


    public function map(Storage_Model_File $model){
        return rtrim($this->getPath(), '/') . '/' . $model->getStoragePath();
    }











    public function getType(){
        return $this->_type;
    }








    public function read($filePath)
    {

        return @file_get_contents($filePath);
    }

    public function write($path, $extension)
    {
        $filePath = $this->generate() . '.' . $extension;
        $path = $this->getPath() . $filePath;

        print_R($path);exit;


        try
        {

            $this->_mkdir(dirname($path));
            $this->_write(APPLICATION_PATH . DS . $path, $data);
            @chmod($path, 0777);
        }

        catch( Exception $e )
        {

            @unlink(APPLICATION_PATH . DS . $path);
            throw $e;
        }

        return $path;
    }




    public function removeFile($path)
    {
        $this->_delete($path);
    }
}