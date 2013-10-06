<?php

class Ikantam_Option
{

    public static function getOption($type, $name, $value = ''){
        return self::getObjectOption($type, $name, $value)->getValue();
    }


    public static function getObjectOption($type, $name, $value = ''){
        return new Ikantam_Model_Option($type, $name, $value);
    }


    public static function getOptions($type){
<<<<<<< HEAD
        return self::getObjectOptions($type)->getArrayValue();
=======
        return self::getObjectOptions($type);
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }


    public static function getObjectOptions($type){
        $object = new Ikantam_Model_Collections_Option();
        return $object->getByType($type);
    }
}
