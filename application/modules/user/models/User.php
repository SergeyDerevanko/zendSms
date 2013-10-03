<?php
class User_Model_User  extends Ikantam_Model_Abstract{

    protected $_groupCollectionModel = null;



    /* FIND PUBLIC FUNCTION */
    public function getByEmail($email){
        $this->_getBackend()->getByEmail($this, $email);
        return $this;
    }



    /* GET PUBLIC FUNCTION */
    public function getGroups(){
        return $this->getGroupCollectionModel();
    }



    /* SET PUBLIC FUNCTION */
    public function create($data){
        $this->setEmail($data['email'])
            ->setPassword($this->_hashPassword($data['password']))
            ->setConfPassword($data['conf_password']);

        $form = new User_Form_Create();

        if($form->isValid($this->getData())){
            echo 'ok';
        } else {
            print_r($form->getErrors());
            echo 'error';
        }
        exit;
        //    ->save();
        $this->addGroup($this->getOption('users', 'default_group', 2));
        return $this;
    }


    public function addGroup($groupId){
        $this->_getBackend()->addGroup($this->getId(), $groupId);
        return $this;
    }


    public function login($email, $password){
        $this->getByEmail($email);
        if($this->getId()){
            if($this->_bcrypt()->verify($password, $this->getPassword())){
                $this->_getSession()->setUserId($this->getId());
                return true;
            } else {
                $this->addErrorText('password', 'error password');
            }
        } else {
            $this->addErrorText('email', 'error email');
        }
        return false;
    }


    public function logout(){
        $this->_getSession()->logout();
    }



    /* PRIVATE FUNCTION */
    private function _hashPassword ($password){
        return $this->_bcrypt()->create($password);
    }


    private function _bcrypt(){
        return new Ikantam_Lib_Crypt_Password_Bcrypt();
    }


    private function _getSession(){
        return User_Model_Session::instance();
    }


    /* LINK MODELS */
    private function getGroupCollectionModel(){
        if(empty($this->_groupCollectionModel)){
            $this->_groupCollectionModel = new Users_Model_Collections_Group();
            $this->_groupCollectionModel->getByUserId($this->getId());
        }
        return $this->_groupCollectionModel;
    }
}