<?php
/**
 * and interface to the usermeta table
 * @package Library
 * @subpackage WP_UserMeta
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_UserMeta extends D5vCode_WP_Table
{	
	public function name ()
	{
		return $this->wpdb ()->usermeta;
	}
	public function key_fields()
	{
		return array('user_id');
	}
	public function get_authors()
	{
		return $this->select(null,"`meta_key` like '%capabilities' AND (`meta_value` like '%administrator%' OR `meta_value` like '%editor%' OR `meta_value` like '%author%' OR `meta_value` like '%contributor%')",null,null,true);
		
	}
}
