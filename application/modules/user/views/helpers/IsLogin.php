<?php
class User_View_Helper_IsLogin extends Zend_View_Helper_Abstract {

    public function isLogin(){
        return User_Model_Session::isLogin();
    }
}