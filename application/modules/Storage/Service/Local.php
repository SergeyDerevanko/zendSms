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


    public function getType(){
        return $this->_type;
    }



    // Accessors

    public function map(Storage_Model_File $model)
    {
        return rtrim($this->getBaseUrl(), '/') . '/' . $model->storage_path;
    }

    public function store(Storage_Model_File $model, $file)
    {
        $path = $this->getScheme()->generate($model->toArray());
        //die($path);
        // Copy file
        try
        {
            $this->_mkdir(dirname(APPLICATION_PATH . DS . $path));
            $this->_copy($file, APPLICATION_PATH . DS . $path);
            @chmod(APPLICATION_PATH . DS . $path, 0777);
        }

        catch( Exception $e )
        {
            @unlink(APPLICATION_PATH . DS . $path);
            throw $e;
        }

        return $path;
    }



    public function read(Storage_Model_File $model)
    {
        $file = APPLICATION_PATH . '/' . $model->storage_path;
        return @file_get_contents($file);
    }

    public function write($path, $extension)
    {
        $path = $this->getPath() .  $this->generate() . '.' . $extension;
        print_R($path);exit;
        $path = $this->getScheme()->generate($model->toArray());

        try
        {
            $this->_mkdir(dirname(APPLICATION_PATH . DS . $path));
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