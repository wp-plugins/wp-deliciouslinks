<?php
/**
 * a subclass to pass mysql calls through to wps mysql routines.
 * @package Library
 * @subpackage WP_Mysql
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Mysql extends D5vCode_Mysql
{
	public function instance()
	{
		if(null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function execute($sql)
	{
		global $wpdb;
		$return = $wpdb->get_results ( $sql , ARRAY_A );
		return $return;
	}
}
