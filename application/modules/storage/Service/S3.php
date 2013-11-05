<?php
class Storage_Service_S3 extends Storage_Service_Abstract
{
    protected $_type = 's3';
    protected $_path;
    protected $_baseUrl;

    protected $_internalService;
    protected $_bucket;
    protected $_streamWrapperName;

    /*
     *   $config = array(
     *   'accessKey', 'secretKey', 'bucket'
     *   )
     */
    public function __construct(array $config){
        $this->_config = $config;

        $this->_internalService = new Zend_Service_Amazon_S3(
            $config['accessKey'],
            $config['secretKey']
        );

        $this->_internalService->registerStreamWrapper($this->_streamWrapperName);

        parent::__construct($config);
    }


    public function getType(){
        return $this->_type;
    }


    public function map(Storage_Model_File $model){
        $path = $model->getPath();
        return 'http://' . $this->_config['bucket'] . '.s3.amazonaws.com/' . $path;
    }


    public function read(Storage_Model_File $model){
        $path = $this->_bucket . '/' . $model->storage_path;
        try {
            $response = $this->_internalService->getObject($path);
            if( !$response ) {
                throw new Zend_Exception('Unable to write file.');
            }
        } catch( Exception $e ) {
            throw $e;
        }
        return $response;
    }


    public function remove(Storage_Model_File $model){
        if( !empty($model->storage_path) ) {
            $path = $this->_bucket . '/' . $model->storage_path;
            try {
                $return = $this->_internalService->removeObject($path);
                if( !$return ) {
                    throw new Zend_Exception('Unable to remove file.');
                }
            } catch( Exception $e ) {
                throw $e;
            }
        }
    }







    public function store(Storage_Model_File $model, $file){
        $path = $this->getScheme()->generate($model->toArray());

        try {
            $return = $this->_internalService->putFile($file, $this->_bucket . '/' . $path, array(
                Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
                'Cache-Control' => 'max-age=864000, public',
            ));
            if( !$return ) {
                throw new Zend_Exception('Unable to store file.');
            }
        } catch( Exception $e ) {
            throw $e;
        }

        return $path;
    }




    public function write(Storage_Model_File $model, $data)
    {
        $path = $this->getScheme()->generate($model->toArray());

        // Prefix path with bucket?
        //$path = $this->_bucket . '/' . $path;

        // Copy file
        try {
            $return = $this->_internalService->putObject($this->_bucket . '/' . $file, $data, array(
                Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
                'Cache-Control' => 'max-age=864000, public',
            ));
            if( !$return ) {
                throw new Zend_Exception('Unable to write file.');
            }
        } catch( Exception $e ) {
            throw $e;
        }

        return $path;
    }

}