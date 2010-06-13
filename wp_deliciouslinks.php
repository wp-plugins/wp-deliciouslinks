<?php
/*
Plugin Name: WP_DeliciousLinkSync ( DISCONTINUED )
Description:DISCONTINUED
Author: DCoda Ltd
Author URI: http://www.dcoda.co.uk
Version: DISCONTINUED

*/
require_once(dirname(__FILE__).'/library/classes/base.php');
class DCodaDeliciousLinks extends dc_base_2_4_0  {
	function init()
	{
		$this->setPath(__FILE__);
		$this->loadClass('deliciouslinks');
	}
}
new DCodaDeliciousLinks();

?>