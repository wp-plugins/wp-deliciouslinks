<?php /*
Plugin Name: &raquo;&raquo;&raquo;&nbsp;DeliciousLinkSync&nbsp;&alpha;
Plugin URI: http://wordpress.org/extend/plugins/wp-deliciouslinks/
Description: Easily synchronize the links list on your blog or multiple
			blogs with the links in your delicious account.
Author: dcoda
Author URI: http://dcoda.co.uk
Version: 4.1.0&alpha;
 */ 
require_once  dirname ( __FILE__ ) . '/library/wordpress/wv15v/application.php';
@include_once (ABSPATH . '/wp-admin/includes/bookmark.php');
	
new wv15v_application ( __FILE__,array('dlinksyncdata','av15v_delicious','bv15v_http') );
