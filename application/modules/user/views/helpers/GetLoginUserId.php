<?php
class User_View_Helper_GetLoginUserId extends Zend_View_Helper_Abstract {

    public function getLoginUserId(){
        return User_Model_Session::loginUserId();
    }
}