<?php
/**
 * Main plugin definition
 * Plugin Name: DeliciousLinkSync
 * Plugin URI: 
 * Description: Easily synchronize the links list on your blog or multiple blogs with the links in your delicious account. 
 * Author: DCoda
 * Author URI: http://www.dcoda.co.uk
 * Version: 1.0.12.D5v
 * @package LinkSync
 * @subpackage Plugin
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
$lib = dirname ( __FILE__ ) . '/library/D5vCode/WP/Plugin.php';
/**
 * check to see if plugin is run from project folder or from parent folder IE as mu plugin.
 */
if (!file_exists ( $lib ))
{
	require_once dirname( __FILE__ ) . '/'.basename(__FILE__,'.php').'/'.basename(__FILE__);
}
else
{
	require_once $lib;
	class DeliciousLinkSync extends D5vCode_WP_Plugin
	{

		public function __construct ( $filename )
		{
			$this->set_name ( "DeliciousLinkSync" );
			parent::__construct ( $filename );
		}
		public function preload_classes ( $classes = array() )
		{
			parent::preload_classes ( array('DLinkSyncData','D5vCode_Http','D5vCode_Http_Delicious') );
		}
	}
	new DeliciousLinkSync ( __FILE__ );
}
