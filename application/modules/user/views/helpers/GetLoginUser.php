<?php
class User_View_Helper_GetLoginUser extends Zend_View_Helper_Abstract {

    public function getLoginUser(){
        return User_Model_Session::loginUser();
    }
}