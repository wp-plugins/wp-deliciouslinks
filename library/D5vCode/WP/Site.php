<?php
/**
 * an interface to the sites table to restricted to a particular site
 * @package Library
 * @subpackage WP_Site
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Site extends D5vCode_WP_Table
{
	public function __construct ( $id=null )
	{
		parent::__construct ();
		if(null === $id)
		{
			$id = $this->wpdb()->siteid;
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
		if (null !== $this->id ())
		{
			if (null === $this->_old_id)
			{
				$this->_old_id = $this->wpdb ()->siteid;
				$this->wpdb ()->siteid = $this->id ();
			}
			else
			{
				$this->wpdb ()->siteid = $this->_old_id;
				$this->_old_id = null;
			}
		}
	}
}
