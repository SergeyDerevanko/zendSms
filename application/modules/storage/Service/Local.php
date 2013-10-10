<?php
class Storage_Service_Local extends Storage_Service_Abstract
{
    protected $_type = 'local';
    protected $_path;

    public function __construct(array $config){
        if( !empty($config['path']) ) {
            $this->_path = APPLICATION_PATH . '/../'. $config['path'] . '/';
        } else {
            $this->_path = APPLICATION_PATH . 'public/storage/';
        }
        parent::__construct($config);
    }



    public function getPath(){
        return $this->_path;
    }


    public function store($filePath, $extension){

        $_filePath = $this->generate() . '.' . $extension;
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







    public function getType(){
        return $this->_type;
    }



    // Accessors

    public function map(Storage_Model_File $model)
    {
        return rtrim($this->getBaseUrl(), '/') . '/' . $model->storage_path;
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

    public function remove(Storage_Model_File $model)
    {
        if( !empty($model->storage_path) )
        {
            $this->_delete(APPLICATION_PATH . DS . $model->storage_path);
        }
    }


    public function removeFile($path)
    {
        $this->_delete($path);
    }
}