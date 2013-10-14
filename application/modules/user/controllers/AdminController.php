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

        $groups = new User_Model_Collections_Group();
        $groups->getAll();

        $this->view->groups = $groups;
        $this->view->users = $users;
    }


    public function deleteuserAction(){
        $data = array('success' => true);

        $userId = $this->getParam('id', 0);
        $user = new User_Model_User($userId);
        if($user->getId()){
            $user->delete();
        }
        $this->_helper->json($data);
    }


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


    public function editusergroupsAction(){
        $data = array('success' => false);
        $userId = $this->getParam('user_id');
        $groups = $this->getParam('groups_id', array());

        $user = new User_Model_User($userId);

        if(!empty($groups) && $user->getId()){
            $user->addArrayGroupId($groups);
            $data['groups_string'] = $user->getGroups()->getStringImplodeColumn('name', ', ');
            $data['success'] = true;
        } else {
            $data['errors']['main'] = 'No Change Groups';
        }
        $this->_helper->json($data);
    }
}