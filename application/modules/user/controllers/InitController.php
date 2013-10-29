<?php

class User_InitController extends Ikantam_Controller_Installer
{
    public function indexAction()
    {
        new Ikantam_Model_Helper('controller', 'user', '/modules/user/controllers/helpers', 'Action_Helper');
        new Ikantam_Model_Helper('view', 'user', '/modules/user/Views/Helpers/GetLoginUser', 'User_View_Helper_GetLoginUser');
        new Ikantam_Model_Helper('view', 'user', '/modules/user/Views/Helpers/GetLoginUserId', 'User_View_Helper_GetLoginUserId');
        new Ikantam_Model_Helper('view', 'user', '/modules/user/Views/Helpers/IsLogin', 'User_View_Helper_IsLogin');

        $connect = Ikantam_Model::getConnect();
        $prefix = Ikantam_Model::getPrefix();

        $sql = "
            CREATE TABLE IF NOT EXISTS `{$prefix}users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `email` varchar(45) NOT NULL,
              `first_name` VARCHAR( 255 ) NULL,
              `last_name` VARCHAR( 255 ) NULL,
              `avatar_id` int(11) NULL,
              `password` varchar(128) NOT NULL,
              `create_date` int(11) NOT NULL,
              `modify_date` int(11) NOT NULL,

              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

            CREATE TABLE IF NOT EXISTS `{$prefix}user_groups` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `slug` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


            INSERT INTO `user_groups` (`id`, `name`, `slug`) VALUES
            (1, 'Admin', 'admin'),
            (2, 'Member', 'member');


            CREATE TABLE IF NOT EXISTS `{$prefix}user_user_group` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `user_group_id` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


            CREATE TABLE IF NOT EXISTS `{$prefix}user_socials` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `type` varchar(32) NOT NULL,
              `social_id` varchar(45) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

            CREATE TABLE IF NOT EXISTS `{$prefix}user_avatar` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `main_file_id` int(11) NOT NULL,
              `small_file_id` int(11) NOT NULL,
              `big_file_id` int(11) NOT NULL,
              `flag_tmp` int(1) NULL DEFAULT  '0',
              `modified_date` int(24) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

        $connect->query($sql);
        echo 'Cood INIT';
        exit;
    }

    /*
     * ROUTES
    <admin_user_options>
            <route>admin/user/options</route>
            <defaults module="user" controller="admin" action="options" />
    </admin_user_options>
    <admin_user_groups>
            <route>admin/user/groups</route>
            <defaults module="user" controller="admin" action="groups" />
    </admin_user_groups>
    <admin_user_manager>
            <route>admin/user/manager</route>
            <defaults module="user" controller="admin" action="manager" />
    </admin_user_manager>

    <admin_user_auth>
            <route>admin/user/auth</route>
            <defaults module="user" controller="admin" action="auth" />
    </admin_user_auth>
     * */


    /*
     * REACT
     <Rsession>
        <class>User_Model_Session</class>
    </Rsession>
     * */


    public function registrationAction(){
        $data = array(
            'email' => 'admin@admin.com',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'avatar_id' => 0,
            'password' => 'adminadmin',
            'conf_password' => 'adminadmin'
        );

        $user = new User_Model_User();
        $user->create($data);
        print_r($user->getErrorsArray());
        exit;
    }


    public function loginAction(){
        $user = new User_Model_User();
        $user->login('admin@admin.com', 'adminadmin');
        print_r($user->getErrorsArray());
        exit;
    }


    public function logoutAction(){
        $this->reactRsessionLogout();
        $this->redirect($this->getUrl('user/auth'));
    }

}