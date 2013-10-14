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



    /* GET PUBLIC FUNCTION */
    public function getHref(){
        return self::$_service->href($this);
    }


    public function getMap(){
        return self::$_service->map($this);
    }


    public function getTimeCreate(){
        return date('Y/m/d', $this->getCreationDate());
    }


    public function getTimeModified(){
        return date('Y/m/d', $this->getModifiedDate());
    }



    /* SET PUBLIC FUNCTION */
    public function create($data){

        $extension = $this->extension($data['name']);

        $storagePath = self::$_service->store($data['path'], $extension);

        $this->setCreationDate(time());
        $this->setUserId($this->reactRsessionLoginUserId());
        $this->setExtension($extension);
        $this->setName($data['name']);
        $this->setStoragePath($storagePath);
        $this->save();
        return $this;
    }


    public function delete(){
        self::$_service->remove($this);
        parent::delete();
    }


    public function pathRegen(){
        self::$_service->move($this);
        return $this;
    }



    /* PRIVATE FUNCTION */
    protected function beforeValid(){
        $this->setModifiedDate(time());
        $this->setSize(filesize($this->getMap()));
    }


    function extension($filename) {
        return substr(strrchr($filename, '.'), 1);
    }


    public function __clone(){
        $this->setId(null);
        $this->setCreationDate(time());
        $this->setStoragePath(self::$_service->copy($this));
        $this->save();
        return $this;
    }
}