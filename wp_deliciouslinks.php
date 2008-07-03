<?php
/*
Plugin Name: WP_DeliciousLinkSync
Plugin URI: http://www.dcoda.co.uk/index.php/tag/wp_deliciouslinksync/
Description: Synchronise You WordPress Links with you delicous links.
Author: DCoda Ltd
Author URI: http://www.dcoda.co.uk
Version: 1.0.0

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