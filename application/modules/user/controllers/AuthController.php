<?php class User_AuthController extends Zend_Controller_Action {

    public function indexAction() {

    }


    public function registrationAction(){
        if($post = $this->getRequest()->getPost()){
            $user = new User_Model_User();
            $user->create($post);
        }
    }


    public function loginAction(){
        $user = new User_Model_User();
        $email = $this->getParam('email');
        $password = $this->getParam('password');
        $user->login($email, $password);
    }


    public function logoutAction(){
        $user = new User_Model_User();
        $user->logout();
    }
}