<<<<<<< HEAD
<?php class User_AuthController extends Ikantam_Controller_Front {

    public function indexAction() {
        if(!$this->reactRsessionIsLogin())
            $this->redirect($this->getUrl('user/auth/login'));
        $this->view->user = $this->reactRsessionLoginUser();
=======
<?php class User_AuthController extends Zend_Controller_Action {

    public function indexAction() {

>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }


    public function registrationAction(){
<<<<<<< HEAD
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
=======
        if($post = $this->getRequest()->getPost()){
            $user = new User_Model_User();
            $user->create($post);
        }
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }


    public function loginAction(){
<<<<<<< HEAD
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
=======
        $user = new User_Model_User();
        $email = $this->getParam('email');
        $password = $this->getParam('password');
        $user->login($email, $password);
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }


    public function logoutAction(){
<<<<<<< HEAD
        $this->reactRsessionLogout();
        $this->redirect($this->getUrl('user/auth'));
=======
        $user = new User_Model_User();
        $user->logout();
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }
}