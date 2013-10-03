<?php

class Ikantam_Option
{

    public static function getOption($type, $name, $value = ''){
        return self::getOption($type, $name, $value)->getValue();
    }


    public static function getObjectOption($type, $name, $value = ''){
        return new Ikantam_Model_Option($type, $name, $value);
    }


    public static function getOptions($type){
        return self::getObjectOptions($type);
    }


    public static function getObjectOptions($type){
        $object = new Ikantam_Model_Collections_Option();
        return $object->getByType($type);
    }
}
