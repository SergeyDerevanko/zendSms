<?php

class User_InitController extends Ikantam_Controller_Installer
{
  public function indexAction()
  {
      $connect = Ikantam_Model::getConnect();
      $prefix = Ikantam_Model::getPrefix();

      $sql = "
            CREATE TABLE IF NOT EXISTS `{$prefix}users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `email` varchar(45) DEFAULT NULL,
              `password` varchar(128) NOT NULL,
              `create_date` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

            CREATE TABLE IF NOT EXISTS `user_groups` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `slug` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


            INSERT INTO `user_groups` (`id`, `name`, `slug`) VALUES
            (1, 'Admin', 'admin'),
            (2, 'Member', 'member');


            CREATE TABLE IF NOT EXISTS `user_user_group` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `user_group_id` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

            ";

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
     * */
}