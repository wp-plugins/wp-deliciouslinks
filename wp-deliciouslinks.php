<?php /*
Plugin Name: DeliciousLinkSync
Plugin URI: http://wordpress.org/extend/plugins/wp-deliciouslinks/
Description: Easily synchronize the links list on your blog or multiple blogs with the links in your delicious account.
Author: dcoda
Author URI: http://dcoda.co.uk
Version: 4.0.1
 */ 
$lib = dirname ( __FILE__ ) . '/library/wordpress/w3v/Application.php';
if (! file_exists ( $lib )) {
	require_once dirname ( __FILE__ ) . '/' . basename ( __FILE__, '.php' ) . '/' . basename ( __FILE__ );
} else {
	require_once $lib;

	@include_once (ABSPATH .  '/wp-admin/includes/bookmark.php');
	
	new w3v_Application ( __FILE__,array('DLinkSyncData','b3v_Http','f1v_Delicious') );
}
