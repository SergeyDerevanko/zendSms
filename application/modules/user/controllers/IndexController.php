<?php class User_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $user = new User_Model_Session();
        $user = $user->loginUser();

    }
}