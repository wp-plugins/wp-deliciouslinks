<?php
/**
 * an insterface to the options table
 * @package Library
 * @subpackage WP_Options
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Options extends D5vCode_WP_Table
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
	
	public function get ( $id )
	{
		if (null === $this->blog_id ())
		{
			return get_option ( $id );
		}
		else
		{
			return get_blog_option ( $this->blog_id () , $id );
		}
	}

	public function set ( $id , $value )
	{
		if (null === $this->blog_id ())
		{
			return update_option ( $id , $value );
		}
		else
		{
			return update_blog_option ( $this->blog_id () , $id , $value );
		}
	}

	public function __construct ( $blog_id = null )
	{
		parent::__construct ( $blog_id , 'options' );
	}
}
