<?php
class User_Model_Avatar  extends Ikantam_Model_Abstract{

    protected $_mainFileModel = null;
    protected $_bigFileModel = null;
    protected $_smallFileModel = null;



    /* GET PUBLIC FUNCTION */
    public function getSmallHref(){
        return $this->getSmallFile()->getHref();
    }


    public function getMainHref(){
        return $this->getMainFile()->getHref();
    }


    public function getBigHref(){
        return $this->getBigFile()->getHref();
    }


    public function getMainFile(){
        return $this->_getMainFileModel();
    }


    public function getBigFile(){
        return $this->_getBigFileModel();
    }


    public function getSmallFile(){
        return $this->_getSmallFileModel();
    }



    /* SET PUBLIC FUNCTION */
    public function create($data){
        $mainFile = new Storage_Model_Image();
        $mainFile->create($data);
        $this->setMainFileId($mainFile->getId());
        $bigFile = clone $mainFile;
        $bigFile->cropAndResize(300, 300);
        $this->setBigFileId($bigFile->getId());
        $smallFile = clone $bigFile;
        $smallFile->resize(50, 50);
        $this->setSmallFileId($smallFile->getId());
        $this->save();
        return $this;
    }


    public function icrop($data){
        $this->getSmallFile()->delete();
        $this->getBigFile()->delete();
        $this->_smallFileModel = null;
        $this->_bigFileModel = null;

        $mainFile = $this->getMainFile();
        $bigFile = clone $mainFile;
        $bigFile->crop($data['x'], $data['y'], $data['w'], $data['h']);
        $bigFile->resize(300,300);
        $this->setBigFileId($bigFile->getId());
        $smallFile = clone $bigFile;
        $smallFile->resize(50, 50);
        $this->setSmallFileId($smallFile->getId());
        $this->save();
    }


    public function tmp(){
        $this->setFlagTmp(1)->save();
        return $this;
    }


    public function unTmp(){
        $this->setFlagTmp(0)->save();
        return $this;
    }


    public function delete(){
        $this->getMainFile()->delete();
        $this->getBigFile()->delete();
        $this->getSmallFile()->delete();
        parent::delete();
    }



    /* LINK MODELS */
    protected function _getMainFileModel(){
        if(!$this->_mainFileModel){
            $this->_mainFileModel = new Storage_Model_Image($this->getMainFileId());
        }
        return $this->_mainFileModel;
    }


    protected function _getBigFileModel(){
        if(!$this->_bigFileModel){
            $this->_bigFileModel = new Storage_Model_Image($this->getBigFileId());
        }
        return $this->_bigFileModel;
    }


    protected function _getSmallFileModel(){
        if(!$this->_smallFileModel){
            $this->_smallFileModel = new Storage_Model_Image($this->getSmallFileId());
        }
        return $this->_smallFileModel;
    }


    /* PRIVATE FUNCTIONS */
    protected function beforeSave(){
        $this->setModifiedDate(time());
    }
}