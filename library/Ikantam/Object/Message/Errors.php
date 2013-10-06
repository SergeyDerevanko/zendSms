<?php

class Ikantam_Object_Message_Errors extends Ikantam_Collection
{
    public function addText($text){
        $error = new Ikantam_Object_Message_Error();
        $error->setText($text);
        $this->addObject($error);
        return $this;
    }


    public function addObject(\Ikantam_Object_Message_Error $object){
        $this->_addItem($object);
        return $this;
    }


    public function getText(){
        $error = '';
        foreach($this->_items as $_error){
            print_r($error);
            $error .= $_error->getText();
        }
        return $error;
    }
}
