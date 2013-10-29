<?php
class User_Model_User  extends Ikantam_Model_Abstract{

    protected $_groupCollectionModel = null;
    protected $_socialCollectionModel = null;
    protected $_avatarModel = null;

    /* FIND PUBLIC FUNCTION */
    public function getByEmail($email){
        $this->_getBackend()->getByEmail($this, $email);
        return $this;
    }


    public function getBySocial($identifier, $provider){
        $this->_getBackend()->getBySocial($this, $identifier, $provider);
        return $this;
    }



    /* GET PUBLIC FUNCTION */
    public function getSmallAvatarHref(){
        return $this->getAvaarModel()->getSmallHref();
    }


    public function getGroups(){
        return $this->getGroupCollectionModel();
    }


    public function getAvatar(){
        return $this->getAvaarModel();
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
        $avatar = new User_Model_Avatar(!empty($data['avatar_id']) ? $data['avatar_id'] : 0);
        if($avatar->getId()){
            $avatar->unTmp();
            $data['avatar_id'] = $avatar->getId();
        } else {
            $data['avatar_id'] = 0;
        }

        $this->setEmail($data['email'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setAvatarId($data['avatar_id'])
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


    public function addSocial($type, $identifier){
        $social = new User_Model_Socials();
        $_data = array(
            'type' => $type,
            'social_id' => $identifier,
            'user_id' => $this->getId()
        );
        $social->create($_data);
        $this->_socialCollectionModel = null;
    }


    public function delete(){
        $this->deleteAllGroups();
        if($this->getAvatarId()) $this->getAvaarModel()->delete();
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


    public function social($data){
        $this->getBySocial($data['identifier'], $data['provider']);
        if($this->getId()) return $this;

        if(!empty($data['email']))
            $this->getByEmail($data['email']);

        if(!$this->getId()){
            $data['conf_password'] = $data['password'] = '123123123';
            $data['email'] = $data['email'] ? $data['email'] :  $data['identifier'] . '@' . $data['provider'] . '.com';
            $this->create($data);
        }

        $this->addSocial($data['provider'], $data['identifier']);
        return $this;
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


    private function getSocialCollectionModel(){
        if(empty($this->_socialCollectionModel)){
            $this->_socialCollectionModel = new User_Model_Collections_Socials();
            $this->_socialCollectionModel->getByUserId($this->getId());
        }
        return $this->_socialCollectionModel;
    }


    private function getAvaarModel(){
        if(empty($this->_avatarModel)){
            $this->_avatarModel = new User_Model_Avatar($this->getAvatarId() ? $this->getAvatarId() : $this->getOption('users', 'default_avatar_id', 0));
        }
        return $this->_avatarModel;
    }

}