<?php
class Action_Helper_IsLogin extends Zend_Controller_Action_Helper_Abstract {

    public function direct(){
        return User_Model_Session::isLogin();
    }
}