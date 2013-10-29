<?php
class User_AdminController extends Ikantam_Controller_Admin
{
    public function indexAction(){
        $this->_redirect($this->getUrl('user/admin/manager'));
        exit;
    }


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


    public function authAction(){
        $this->initOptionForForm('users', 'auth_social_network', 0);
        $this->initOptionForForm('users', 'debug_mode', 0);

        $this->initOptionForForm('users', 'auth_yahoo', 0);
        $this->initOptionForForm('users', 'auth_google', 0);
        $this->initOptionForForm('users', 'auth_facebook', 0);
        $this->initOptionForForm('users', 'auth_twitter', 0);
        $this->initOptionForForm('users', 'auth_live', 0);
        $this->initOptionForForm('users', 'auth_myspace', 0);
        $this->initOptionForForm('users', 'auth_linkedin', 0);
        $this->initOptionForForm('users', 'auth_foursquare', 0);

        $this->initOptionForForm('users', 'auth_yahoo_keys_id', '');
        $this->initOptionForForm('users', 'auth_yahoo_keys_secret', '');

        $this->initOptionForForm('users', 'auth_google_keys_id', '29007505579.apps.googleusercontent.com');
        $this->initOptionForForm('users', 'auth_google_keys_secret', '2Igtq25BpUWWB9aBlwkL76G1');
        $this->initOptionForForm('users', 'auth_google_keys_scope', 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');

        $this->initOptionForForm('users', 'auth_facebook_keys_id', '368109863316611');
        $this->initOptionForForm('users', 'auth_facebook_keys_secret', '1d4caf580d5e18f3d8f6d7da8b1a7982');
        $this->initOptionForForm('users', 'auth_facebook_keys_scope', 'email, user_birthday, user_about_me');

        $this->initOptionForForm('users', 'auth_twitter_keys_key', 'TXCt81rDAbeV0FX7zws7Zw');
        $this->initOptionForForm('users', 'auth_twitter_keys_secret', 'hsxKTyIH5TY95T5FhTkEIHfd5jYnEJbPS19UL3qKnw');

        $this->initOptionForForm('users', 'auth_live_keys_id', '');
        $this->initOptionForForm('users', 'auth_live_keys_secret', '');

        $this->initOptionForForm('users', 'auth_myspace_keys_key', '');
        $this->initOptionForForm('users', 'auth_myspace_keys_secret', '');

        $this->initOptionForForm('users', 'auth_linkedin_keys_key', '0ehh0o1d6dac');
        $this->initOptionForForm('users', 'auth_linkedin_keys_secret', 'o5YPof6dsQYGBplo');
        $this->initOptionForForm('users', 'auth_linkedin_keys_scope', 'r_basicprofile, r_emailaddress, r_contactinfo');

        $this->initOptionForForm('users', 'auth_myspace_keys_id', '');
        $this->initOptionForForm('users', 'auth_myspace_keys_secret', '');


    }
}