<?php
class User_Form_Create extends Zend_Form {
    
    public function init ()
    {
        $baseFilters = array('StringTrim', 'StripTags');

        
        $lengthValidator = new Zend_Validate_StringLength(array('max' => 45));
        $lengthValidator->setMessage('Value must be less than %max%' , Zend_Validate_StringLength::TOO_LONG);

        $email = $this->createElement('text', 'email');
        $email->setRequired()
              ->addFilter('StringTrim')  
              ->addValidators(array('EmailAddress'));
              

        $password = $this->createElement('text', 'password');
        $password
            ->addFilter('StringTrim')
            ->setRequired('true');

        $conf_password = $this->createElement('text', 'conf_password');
        $conf_password
            ->addFilter('StringTrim')
            ->setRequired('true')
            ->addValidator(new Zend_Validate_Identical('password'));

        $id = $this->clearElements('id', 'id');

        $this->addElements(array(
            $email,
            $password,
            $conf_password,
            $id
        ));
              
                    
    }  
}