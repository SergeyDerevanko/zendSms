<?php class User_AuthController extends Ikantam_Controller_Front {

    public function indexAction() {
        if(!$this->reactRsessionIsLogin())
            $this->redirect($this->getUrl('user/auth/login'));
        $this->view->user = $this->reactRsessionLoginUser();
    }


    public function registrationAction(){
        if($this->reactRsessionIsLogin())
            $this->redirect($this->getUrl('user/auth'));

        $mess_error = false;
        $mess_success = false;

        if($post = $this->getRequest()->getPost()){
            $user = new User_Model_User();
            if(!$user->create($post)){
                $mess_error = $user->getErrorsGrouping('debug');
            } else {
                $mess_success = 'Success';
            }
        }

        $this->view->mess_error = $mess_error;
        $this->view->mess_success = $mess_success;
    }


    public function loginAction(){
        if($this->reactRsessionIsLogin())
            $this->redirect($this->getUrl('user/auth'));

        $mess_error = false;
        $mess_success = false;

        if($post = $this->getRequest()->getPost()){

            $email = $this->getParam('email', '');
            $password = $this->getParam('password', '');

            $user = new User_Model_User();
            if($user->login($email, $password)){
                $this->redirect($this->getUrl('user/auth'));
            } else{
                $mess_error = $user->getErrorsGrouping('debug');
            }
        }

        $this->view->mess_error = $mess_error;
        $this->view->mess_success = $mess_success;
    }


    public function logoutAction(){
        $this->reactRsessionLogout();
        $this->redirect($this->getUrl('user/auth'));
    }
}