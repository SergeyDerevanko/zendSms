<?php

class Ikantam_Form extends Zend_Form
{

    public function setValidatorDescribe($describes){
        foreach($describes as $index => $describe){
            $element = $this->getElement($index);
            if(!$element){
                $this->addElement('text', $index);
                $element = $this->getElement($index);
            }

            if(!$describe['NULLABLE'])
                $element->isRequired();
            if($index != 'id' && $describe['DATA_TYPE'] == 'int')
                $element->addValidator('Int');

        }
    }
}
