<?php
class Storage_Model_File  extends Ikantam_Model_Abstract{

    protected static $_service = null;


    public function __construct($id = null){
        parent::__construct($id);
        if(!self::$_service){
            $service = new Storage_Model_Service($this->getOption('storage', 'service_id', 1));
            $class = $service->getClass();
            self::$_service = new $class($this->getOptions('storage'));
        }
    }



    /* SET PUBLIC FUNCTION */
    public function create($data){
        $extension = $this->extension($data['name']);
        $this->setCreationDate(time());
        $this->setUserId($this->reactRsessionLoginUserId());
        $this->setExtension($extension);
        $this->setName($data['name']);
        $this->setStoragePath(self::$_service->store($data['path'], $extension));
        $this->save();
        return $this;
    }

    function extension($filename) {
        return substr(strrchr($filename, '.'), 1);
    }

    /* PRIVATE FUNCTION */
    protected function beforeValid(){
        $this->setModifiedDate(time());
    }
}