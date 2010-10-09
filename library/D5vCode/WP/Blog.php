<?php
/**
 * a class to access data in the blogs table for a specified blog
 * @package Library
 * @subpackage WP_Blog
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Blog extends D5vCode_WP_Blogs
{
	public function __construct ( $id= null )
	{
		parent::__construct ();
		if(null === $id)
		{
			$id = $this->wpdb()->blog_id;
		}
		$this->set_id ( $id );
	}
	private $_id = null;
	protected function id ()
	{
		return $this->_id;
	}
	protected function set_id ( $id )
	{
		$this->_id = $id;
	}
	private $_old_id = null;
	public function swap ()
	{
		global $current_blog;
		if (null !== $this->id ())
		{
			if (null === $this->_old_id)
			{
				$this->_old_id = $this->wpdb ()->set_blog_id($this->id ());
			}
			else
			{
				$this->wpdb ()->set_blog_id($this->_old_id );
				$this->_old_id = null;
			}
			wp_cache_reset();
		}
	}
}
