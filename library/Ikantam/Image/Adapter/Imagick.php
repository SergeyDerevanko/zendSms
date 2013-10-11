<?php
class Ikantam_Image_Adapter_Imagick extends Ikantam_Image{

    protected $_resource;
    public function __construct($options = array()){
        if( !class_exists('Imagick', false) ) {
            throw new Ikantam_Image_Exception('Imagick library is not installed');
        }

        parent::__construct($options);
    }

    /* Options */

    public function getFile(){
        $this->_checkOpenImage();
        return $this->_resource->getImageFilename();
    }


    public function setFile($file){
        $this->_checkOpenImage();
        $this->_resource->setImageFilename($file);
        return $this;
    }


    public function getFormat(){
        $this->_checkOpenImage();
        return $this->_resource->getImageFormat();
    }


    public function setFormat($format){
        $this->_checkOpenImage();
        $format = strtoupper($format);
        $this->_resource->setImageFormat($format);
    }


    public function getHeight(){
        $this->_checkOpenImage();
        return $this->_resource->getImageHeight();
    }


    public function getWidth(){
        $this->_checkOpenImage();
        return $this->_resource->getImageWidth();
    }



    /* Actions */
    public function create($width, $height, $format = 'png'){
        $this->destroy();
        $resource = new Imagick();
        $resource->setResolution($width, $height);
        $resource->newImage(100, 100, new ImagickPixel('none'), $format);
        $resource->setImageFormat($format);
        $this->_resource = $resource;
        return $this;
    }


    public function open($file){
        $this->destroy();
        $this->_resource = new Imagick();
        $return = $this->_resource->readImage($file);
        if( !$return || !$this->_checkOpenImage(false) ) {
            $this->_resource = null;
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to open image "%s"', $file));
        }
        return $this;
    }


    public function write($file = null, $type = 'jpeg'){
        $this->_checkOpenImage();

        if( $type == 'jpg' ) {
            $type = 'jpeg';
        }

        $type = strtoupper($type);
        if( $type !== $this->_resource->getImageFormat() ) {
            $this->_resource->setImageFormat($type);
        }

        if( null !== $this->_quality ) {
            $this->_resource->setImageCompressionQuality($this->_quality);
        }

        if( null === $file ) {
            $return = $this->_resource->writeImage();
        } else {
            $return = $this->_resource->writeImage($file);
        }

        if( !$return ) {
            if( !$file ) {
                $file = $this->_resource->getImageFilename();
            }
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to write image to file "%s"', $file));
        }
        return $this;
    }


    public function destroy(){
        if( $this->_checkOpenImage(false) ) {
            $this->_resource->destroy();
        }
        $this->_resource = null;
        return $this;
    }


    public function output($type = 'jpeg', $buffer = false){
        $this->_checkOpenImage();

        if( $type == 'jpg' ) {
            $type = 'jpeg';
        }
        $type = strtoupper($type);
        if( $type !== $this->_resource->getImageFormat() ) {
            $this->_resource->setImageFormat($type);
        }

        if( null !== $this->_quality ) {
            $this->_resource->setImageCompressionQuality($this->_quality);
        }

        if( $buffer ) {
            return (string) $this->_resource;
        } else {
            echo $this->_resource;
        }

        return $this;
    }


    public function resize($width, $height, $aspect = true){
        $this->_checkOpenImage();

        $imgW = $this->_resource->getImageWidth();
        $imgH = $this->_resource->getImageHeight();

        if( $aspect ) {
            list($width, $height) = self::_fitImage($imgW, $imgH, $width, $height);
        }

        try {
            $return = $this->_resource->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to resize image: %s',
                $e->getMessage()), $e->getCode());
        }

        if( !$return ) {
            throw new Ikantam_Image_Adapter_Exception('Unable to resize image');
        }

        return $this;
    }


    public function crop($x, $y, $w, $h){
        $this->_checkOpenImage();

        try {
            $return = $this->_resource->cropImage($w, $h, $x, $y);
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to crop image: %s',
                $e->getMessage()), $e->getCode());
        }

        if( !$return ) {
            throw new Ikantam_Image_Adapter_Exception('Unable to crop image');
        }

        return $this;
    }


    public function resample($srcX, $srcY, $srcW, $srcH, $dstW, $dstH){
        $this->_checkOpenImage();

        try {
            $return = $this->_resource->cropImage($srcW, $srcH, $srcX, $srcY);
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to resample image: %s',
                $e->getMessage()), $e->getCode());
        }

        if( !$return ) {
            throw new Ikantam_Image_Adapter_Exception('Unable to resample image');
        }

        try {
            $return = $this->_resource->resizeImage($dstW, $dstH, Imagick::FILTER_LANCZOS, 1);
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to resample image: %s',
                $e->getMessage()), $e->getCode());
        }

        if( !$return ) {
            throw new Ikantam_Image_Adapter_Exception('Unable to resample image');
        }

        return $this;
    }


    public function rotate($angle){
        try {
            $return = $this->_resource->rotateImage(new ImagickPixel('none'), $angle);
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to rotate image: %s',
                $e->getMessage()), $e->getCode());
        }

        return $this;
    }


    public function flip($horizontal = true){
        try {
            if( $horizontal ) {
                $return = $this->_resource->flopImage();
            } else {
                $return = $this->_resource->flipImage();
            }
        } catch( ImagickException $e ) {
            throw new Ikantam_Image_Adapter_Exception(sprintf('Unable to flip image: %s',
                $e->getMessage()), $e->getCode());
        }

        return $this;
    }



    protected function _checkOpenImage($throw = true){
        if( !($this->_resource instanceof Imagick) ) {
            if( $throw ) {
                throw new Ikantam_Image_Adapter_Exception('No open image to operate on.');
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}