<?php 

/*
Plugin Name: Photo Assistant
Plugin URI: https://github.com/kkoppenhaver/photo-assistant
Description: A WordPress plugin that helps you pick the right stock photo based on the content of your post
Author: Keanan Koppenhaver
Twitter: @kkoppenhaver
Author URI: http://keanankoppenhaver.com
Version: 0.1
License: GPL
Copyright: Keanan Koppenhaver
*/

define('PA_ADMIN_URL', plugins_url('admin/', __FILE__));  


/*
*  pa_before_theme
*  Load admin files before the theme loads
*
*  @since 1.0
*/
	
function pa_plugin_init(){
	
	include_once('admin/admin.php');	

	wp_enqueue_style( 'admin-css', PA_ADMIN_URL. 'css/admin.css');
}  

add_action( 'admin_init', 'pa_plugin_init' );


?>