<?php
class User_Form_Create extends Ikantam_Form {
    
    public function init ()
    {
        $prefix = Ikantam_Model::getPrefix();
        $baseFilters = array('StringTrim', 'StripTags');

        
        $lengthValidator = new Zend_Validate_StringLength(array('max' => 45));
        $lengthValidator->setMessage('Value must be less than %max%' , Zend_Validate_StringLength::TOO_LONG);


        $email = $this->createElement('text', 'email');
        $email->setRequired()
              ->addFilters($baseFilters)
              ->addValidator('EmailAddress')
              ->addValidator($lengthValidator)
              ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                'table' => "{$prefix}users",
                'field' => 'email'
              )));
              

        $password = $this->createElement('text', 'password');
        $password
            ->setRequired()
            ->addFilters($baseFilters)
            ->addValidator($lengthValidator);

        $conf_password = $this->createElement('text', 'conf_password');
        $conf_password
            ->addFilters($baseFilters)
            ->setRequired()
            ->addValidator(new Zend_Validate_Identical('password'))
            ->addValidator($lengthValidator);

        $id = $this->createElement('text', 'id');

        $this->addElements(array(
            $email,
            $password,
            $conf_password,
            $id
        ));
    }  
}