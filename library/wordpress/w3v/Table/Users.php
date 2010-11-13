<?php
class w3v_Table_Users extends w3v_Table
{

	public function name ()
	{
		return $this->wpdb ()->users;
	}
	private $_key_fields = null;
	public function key_fields ()
	{
		if(null === $this->_key_fields)
		{
			$keys = $this->db()->show_indexes($this->name());
			$this->_key_fields = array();
			foreach($keys as $key)
			{
				if($key['Key_name'] == 'PRIMARY')
				{
					$this->_key_fields[]=$key['Column_name'];
				}
			}
		}
		return $this->_key_fields;
	}

	public function get_ids ()
	{
		return $this->select(null,"`deleted`= 0 AND `user_status` = 0;");
	}
	
}
