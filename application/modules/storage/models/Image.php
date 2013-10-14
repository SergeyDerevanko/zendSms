<?php
class Storage_Model_Image  extends Storage_Model_File{

    protected $_file = null;

    /* SET PUBLIC FUNCTION */
    public function resize($w, $h){
        $this->getImg()
            ->resize($w, $h)
            ->write($this->getMap());
        $this->pathRegen()->save();
        return $this;
    }


    public function crop($x, $y, $w, $h){
        $this->getImg()
            ->crop($x, $y, $w, $h)
            ->write($this->getMap());
        $this->pathRegen()->save();
        return $this;
    }



    /* PRIVATE FUNCTION */
    public function getImg(){
        if(!$this->_file){
            $this->_file = Ikantam_Image::factory();
            $this->_file->open($this->getMap());
        }
        return $this->_file;
    }
}