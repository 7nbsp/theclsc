<?php
/*
Plugin Name: Electric Studio Auto Post Expire
Plugin URI: http://www.electricstudio.co.uk
Description: Set an expiry date for posts.
Version: 1.3
Author: James Irving-Swift
Author URI: http://www.irving-swift.com
License: GPL2
*/

include 'lib/install.php';
include 'lib/options.php';
require_once 'lib/dbfunctions.php';
require_once 'lib/metabox.php';

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'ES_auto_expire_post_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__,'ES_auto_expire_post_remove');

add_action('wp_es_expire_posts','electric_studio_auto_post_expire');

do_action('wp_es_expire_posts');

function electric_studio_auto_post_expire(){
  $expiredPosts = get_expiring_posts();
  foreach($expiredPosts as $thisPostID){
    expire_post($thisPostID);
  }
  
}

?>
