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

define('PA_PATH', plugin_dir_path(__FILE__));
define('PA_ADMIN_URL', plugins_url('admin/', __FILE__));  


/*
*  pa_before_theme
*  Load admin files before the theme loads
*
*  @since 1.0
*/
	
function pa_plugin_init(){
	
	include_once('admin/admin.php');	

}  

add_action( 'admin_init', 'pa_plugin_init' );

/**
* pa_media_popup_content
* Add pop up content to edit, new and post pages
*
* @since 1.0
*/

add_action( 'admin_head-post.php',  'pa_media_popup_content' );
add_action( 'admin_head-post-new.php',  'pa_media_popup_content' );
add_action( 'admin_head-edit.php',  'pa_media_popup_content' );

function pa_media_popup_content() {
   wp_enqueue_style( 'admin-css', PA_ADMIN_URL. 'css/admin.css');
   wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
   wp_enqueue_script('jquery');
   ?>

   <div id="pa-photos-modal" style="display:none;">
     <?php include( PA_PATH . 'admin/includes/pa-photos.php');	?> 
   </div>
<?php
}


?>