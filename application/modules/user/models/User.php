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
<<<<<<< HEAD
            ->setPassword($data['password'])
            ->setConfPassword($data['conf_password'])
            ->setCreateDate(time());

        $this->setValidClass('User_Form_Create');

        if($this->save()){
            $this->addGroup($this->getOption('users', 'default_group', 2));
            return $this;
        }
        return false;
=======
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
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }


    public function addGroup($groupId){
        $this->_getBackend()->addGroup($this->getId(), $groupId);
<<<<<<< HEAD
        return $this;
    }


    public function delete(){
        $this->_getBackend()->deleteGroup($this->getId());
        parent::delete();
=======
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
        return $this;
    }


    public function login($email, $password){
        $this->getByEmail($email);
        if($this->getId()){
            if($this->_bcrypt()->verify($password, $this->getPassword())){
                $this->_getSession()->setUserId($this->getId());
                return $this;
            } else {
                $this->addTextError('password', 'error password');
            }
        } else {
            $this->addTextError('email', 'error email');
        }
        return false;
    }


    public function logout(){
        $this->_getSession()->logout();
    }


<<<<<<< HEAD


=======
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95

    /* PRIVATE FUNCTION */
    public function beforeValid(){
        $this->setModifyDate(time());
    }


    protected function beforeSave(){
        if(strlen($this->getData('password')) < 20)
            $this->setPassword($this->_hashPassword($this->getpassword()));
    }


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