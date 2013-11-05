<?php
class Storage_Model_Image  extends Storage_Model_File{

    protected $_file = null;

    /* GET PUBLIC FUNCTION */
    public function getHeight(){
        return $this->getImg()->getHeight();
    }


    public function getWidth(){
        return $this->getImg()->getWidth();
    }



    /* SET PUBLIC FUNCTION */
    public function resize($w, $h){
        $this->getImg()
            ->resize($w, $h)
            ->write($this->getMap());
        $this->pathRegen()->save();
        return $this;
    }


    public function cropAndResize($w, $h){
        $h_i = $this->getHeight();
        $w_i = $this->getWidth();

        if(($h_i / $w_i) * $h >= $w){
            $_w = $w;
            $_h = ($h_i/$w_i)*$h;
        } else {
            $_h = $h;
            $_w = ($w_i/$h_i)*$w;
        }
        $this->resize($_w, $_h);
        $this->crop(0, 0, $w, $h);
        return $this;
    }


    public function crop($x, $y, $w, $h){
        $this->getImg()
            ->crop($x, $y, $w, $h)
            ->write($this->getMap());
        $this->pathRegen()->save();
        return $this;
    }


    public function icrop($x, $y, $w, $h, $_w){
        $opt = 1 / ($this->getWidth() / $_w);
        $this->crop($x * $opt, $y * $opt, $w * $opt, $h * $opt);
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