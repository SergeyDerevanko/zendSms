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
        $this->setCreationDate(time());
        $this->setExtension($this->extension($data['name']));
        $this->setName($data['name']);
        print_r(self::$_service->write($data['path']));
    }

    function extension($filename) {
        return substr(strrchr($filename, '.'), 1);
    }

    /* PRIVATE FUNCTION */
    protected function beforeValid(){
        $this->setModifiedDate(time());
    }
}