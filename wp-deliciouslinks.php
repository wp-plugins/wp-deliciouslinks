<?php /*
Plugin Name: DeliciousLinkSync
Plugin URI: http://wordpress.org/extend/plugins/wp-deliciouslinks/
Description: Easily synchronize the links list on your blog or multiple blogs with the links in your delicious account.
Author: dcoda
Author URI: http://dcoda.co.uk
Version: 4.1.0a
 */ 
require_once  dirname ( __FILE__ ) . '/library/wordpress/wv15v/Application.php';
@include_once (ABSPATH .  '/wp-admin/includes/bookmark.php');
	
new wv15v_Application ( __FILE__,array('DLinkSyncData','fv15v_Delicious','bv15v_Http') );
