<?php class User_AuthController extends Ikantam_Controller_Front {

    private $_HybridAuth;



    public function indexAction() {
        if(!$this->_helper->isLogin())
            $this->redirect($this->getUrl('user/auth/login'));
        $this->view->user = $this->reactRsessionLoginUser();
    }


    public function registrationAction(){
        if($this->_helper->isLogin())
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
        if($this->_helper->isLogin())
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


    public function socialAction(){

        if($this->getOption('users', 'auth_social_network', 0)){
            $this->_HybridAuth = new User_Hybrid_Auth();
            $provider = $this->getParam('provider', false);
            if(!$provider) $this->_helper->show404();
            try {
                $adapter = $this->_HybridAuth->authenticate($provider);
            } catch (Excpetion $ex) {
                $this->_helper->show404();
            }

            $profile = $adapter->getUserProfile();

            $data = array(
                'provider' => $provider,
                'identifier' => $profile->identifier,
                'email' => $profile->email
            );

            $user = new User_Model_User();
            $user->social($data);
            print_r($user);
            //$handler = new User_Model_User_User_HybridProfileHandler($adapter);
            //$handler->authenticate();

            //$session = User_Model_Session::instance();
            //$url = $session->getFlashData('r_url') ? $session->getFlashData('r_url') : $this->getRouteUrl('home');
            //$this->redirect($url);
            print_r($adapter->getUserProfile());
            exit;
        }
        $this->_helper->show404();
    }


    public function logoutAction(){
        $this->_helper->logout();
        $this->redirect($this->getUrl('user/auth'));
    }


    public function socialendpointAction(){
        User_Hybrid_Endpoint::process();
    }
}