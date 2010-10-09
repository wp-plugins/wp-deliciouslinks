<?php
/**
 * an insterface to the sitemeta table.
 * @package Library
 * @subpackage WP_SiteMeta
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_SiteMeta extends D5vCode_WP_Table
{
	public function post ($key)
	{
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			$post = $_POST;
			if (array_key_exists('submit', $post)) {
				unset($post['submit']);
			}
			return $this->set($key, $post);
		}
	}
	
	public function name ()
	{
		return $this->wpdb ()->sitemeta;
	}

	public function get ( $id )
	{
		$this->wp_site ()->swap ();
		$return = get_site_option ( $id );
		$this->wp_site ()->swap ();
		return $return;
	}

	public function set ( $id , $value )
	{
		$this->wp_site ()->swap ();
		update_site_option ( $id , $value );
		$this->wp_site ()->swap ();
	}
	private $_wp_site = null;

	protected function set_wp_site ( $id )
	{
		$this->_wp_site = new D5vCode_WP_Site ( $id );
	}

	protected function wp_site ()
	{
		return $this->_wp_site;
	}

	public function __construct ( $id=null )
	{
		parent::__construct ();
		$this->set_wp_site ( $id );
	}
}
