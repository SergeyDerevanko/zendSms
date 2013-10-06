<?php

class Ikantam_Model_Errors extends Ikantam_Object
{

    public function getErrorsGrouping($typeGrouping = 'fields_string'){

            switch($typeGrouping){
                case 'fields_string':
                    $array = array();
                    foreach($this->getData() as $index => $error){
                        $array[$index] = $error->getText();
                    }
                    return $array;
                    break;
                case 'string':
                    $string = '';
                    foreach($this->getData() as $error){
                        $string .= $error->getText();
                    }
                    return $string;
                    break;
                case 'debug':

                    $string = '';
                    foreach($this->getData() as $index => $error){
                        $_error = $index . ':' . $error->getText() . "  ";
                        $string .= $_error;
                    }
                    return $string;
                    break;
                default:
                    break;

        }

    }


    public function &getField($index){
        if(!$this->getData($index))
            $this->setData($index, new Ikantam_Object_Message_Errors());
        return $this->getData($index);
    }


    public function addText($index, $text){
        $errors = $this->getField($index);
        $errors->addText($text);
        return $this;
    }


    public function addObject($index, \Ikantam_Object_Message_Error $object){
        $this->getField($index)->addObject($object);
        return $this;
    }

}
