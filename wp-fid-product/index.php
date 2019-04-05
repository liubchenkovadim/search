<?php

/*
Plugin Name:Test plug
*/


function pluginprefix_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix.'testing';

    if($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name){

      $sql = "CREATE TABLE IF NOT EXISTS `wp_testing` (
              `id` INT(11) NOT NULL AUTO_INCREMENT , 
              `name` VARCHAR(40) NOT NULL , 
              `text` TEXT NOT NULL , PRIMARY KEY (`id`)
              ) ENGINE = MyISAM;
   "  ;

      $wpdb->query($sql);
    }
}
register_activation_hook(__FILE__, 'pluginprefix_install');


add_option('test_on_page', 5);

function pluginprefix_deactivation()
{


 delete_option('test_on_page');
}
register_deactivation_hook(__FILE__, 'pluginprefix_deactivation');


function testing_undelete()
{
    global $wpdb;
    $table_name = $wpdb->prefix.'testing';
    $sql = "DROP TABLE IF EXISTS  $table_name";
    $wpdb->query($sql);
}
register_uninstall_hook(__FILE__,'untesting_delete');
function testing_admin_menu()
{
   // add_menu_page('Отзывы','Отзывы',8, 'testing', 'testing_editor');
   // add_submenu_page('testing','Отзывы','Отзывы',8, 'testing2', 'testing_editor');
     add_posts_page('Отзывы','Фид',8, 'testing', 'testing_editor');

}
add_action('admin_menu','testing_admin_menu' );

function testing_editor()
{

    switch($_GET['c']){
       case 'add':
           $action = 'add';
           break;
       case 'edit' :
           $action = 'edit';
           break;
       default:
           $action = 'all';
           break;
   }

   include_once ("includs/$action.php");
}

function testing_short(){
    ob_start();
    include_once ("includs/intro.php");
    return ob_get_clean();
}
add_filter('widget_text', 'do_shortcode');
add_shortcode('testing', 'testing_short');