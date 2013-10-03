<?php

class Storage_InitController extends Ikantam_Controller_Installer
{
  public function indexAction()
  {
      $connect = Ikantam_Model::getConnect();
      $prefix = Ikantam_Model::getPrefix();

      $sql = "
            DROP TABLE IF EXISTS `{$prefix}storage_services`;
            CREATE TABLE IF NOT EXISTS `{$prefix}storage_services` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
              `class` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

            INSERT INTO `{$prefix}storage_services` (`id`, `title`, `class`) VALUES
            (1, 'Local Storage', 'Storage_Service_Local'),
            (2, 'Amazon S3', 'Storage_Service_S3');



            DROP TABLE IF EXISTS `{$prefix}storage_files`;
             CREATE TABLE `{$prefix}storage_files` (
              `id` int(10) unsigned NOT NULL auto_increment,
              `type` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NULL,

              `parent_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci default NULL,
              `user_id` int(10) unsigned default NULL,
              `creation_date` datetime NOT NULL,
              `modified_date` datetime NOT NULL,

              `storage_path` varchar(255) NOT NULL,
              `extension` varchar(8) NOT NULL,
              `name` varchar(255) default NULL,
              `mime_major` varchar(64) NOT NULL,
              `mime_minor` varchar(64) NOT NULL,
              `size` bigint(20) unsigned NOT NULL,
              `hash` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
              PRIMARY KEY  (`id`));
            ";

      $connect->query($sql);
      echo 'Cood INIT';
      exit;
  }

    /*
     * ROUTES
    <storage_options>
            <route>admin/storage/options</route>
            <defaults module="Storage" controller="admin" action="options" />
    </storage_options>
     * */
}