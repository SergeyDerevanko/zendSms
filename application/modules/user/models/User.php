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


    public function isInGroupById($id){
        foreach($this->getGroups() as $group){
            if($id == $group->getId())
                return true;
        }
        return false;
    }



    /* SET PUBLIC FUNCTION */
    public function create($data){
        $this->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setConfPassword($data['conf_password'])
            ->setCreateDate(time());

        $this->setValidClass('User_Form_Create');

        if($this->save()){
            $this->addGroup($this->getOption('users', 'default_group', 2));
            return $this;
        }
        return false;
    }


    public function addGroup($groupId){
        if(!$this->isInGroupById($groupId)){
            $this->_getBackend()->addGroup($this->getId(), $groupId);
            $this->_groupCollectionModel = null;
        }

        return $this;
    }


    public function addArrayGroupId($groups){
        if(is_array($groups)){
            $this->deleteAllGroups();
            foreach($groups as $group){
                $this->addGroup($group);
            }
        }
        return $this;
    }


    public function delete(){
        $this->deleteAllGroups();
        parent::delete();
        return $this;
    }


    public function deleteAllGroups(){
        $this->_getBackend()->deleteGroup($this->getId());
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
            $this->_groupCollectionModel = new User_Model_Collections_Group();
            $this->_groupCollectionModel->getByUserId($this->getId());
        }
        return $this->_groupCollectionModel;
    }
}