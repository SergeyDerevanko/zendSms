<?php
class User_AdminController extends Ikantam_Controller_Admin
{
    public function optionsAction(){
        $this->initOptionForForm('users', 'default_group', 1);

        $groups = new User_Model_Collections_Group();
        $groups->getAll();

        $this->view->groups = $groups;
    }


    public function managerAction(){
        $users = new User_Model_Collections_User();
        $users->getAll();

        $this->view->users = $users;
    }


<<<<<<< HEAD
    public function deleteuserAction(){
        $data = array('success' => true);

        $userId = $this->getParam('user_id', 0);
        $user = new User_Model_User($userId);
        if($user->getId()){
            $user->delete();
        }
        $this->_helper->json($data);
    }


=======
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    public function groupsAction(){
        if($post = $this->getRequest()->getPost()){
            $group = new User_Model_Group();
            $group->create($post);
        }

        $groups = new User_Model_Collections_Group();
        $groups->getAll();

        $this->view->groups = $groups;
    }


    public function deletegroupAction(){
        $groupId = $this->getParam('group_id', 2);
        $group = new User_Model_Group();
        $group->getById($groupId);
        if($group->getId()){
            $group->delete();
        }
    }
}