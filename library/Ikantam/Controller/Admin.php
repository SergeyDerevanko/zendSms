<?php

abstract class Ikantam_Controller_Admin extends Ikantam_Controller_Front
{

	public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{

		parent::__construct($request, $response, $invokeArgs);
        $this->_helper->layout->setLayout('admin');
	}


    public function initOptionForForm($type, $name, $value = ''){
        ${$name} = new Ikantam_Model_Option($type, $name, $value);
        ${$name}->setValue($this->getParam($name, ${$name}->getValue()))->save();
        $this->view->{$name} = ${$name}->getValue();
    }
}
