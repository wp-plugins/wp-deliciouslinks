<?php /*
Plugin Name: DeliciousLinkSync
Plugin URI: http://wordpress.org/extend/plugins/wp-deliciouslinks/
Description: With changes to the delicious api and little time to maintain this plugin it has been discontinued
Author: dcoda
Author URI: http://dcoda.co.uk
Version: DISCONTIUNED
 */ 
$lib = dirname ( __FILE__ ) . '/library/wordpress/w8v/Application.php';
if (! file_exists ( $lib )) {
	require_once dirname ( __FILE__ ) . '/' . basename ( __FILE__, '.php' ) . '/' . basename ( __FILE__ );
} else {
	require_once $lib;

	@include_once (ABSPATH .  '/wp-admin/includes/bookmark.php');
	
	new w8v_Application ( __FILE__,array('DLinkSyncData','b8v_Http','f2v_Delicious') );
}
