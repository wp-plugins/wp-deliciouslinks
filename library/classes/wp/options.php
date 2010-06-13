<?php
class dc_wp_options_1_0_2 extends dc_base_2_4_0 {
	var $values = array();
	var $_name = null;
	function init() {
	$this->load();
	}
	function save($name=null)
	{
		if (is_null($name)) {
			$name = $this->projectClass;
		}
		update_option($name, $this->values);
	}
	function load($name = null)
	{
		if (is_null($name)) {
			$name = $this->projectClass;
		}
		$this->values=get_option($name);
	}

	function delete($name =null)
	{
		if (is_null($name)) {
			$name = $this->projectClass;
		}
		delete_option($name);
		$this->load();
	}
	function field_prefix()
	{
		return $this->projectClass.'_';
	}
	function merge($array)
	{
		if(count($array)>0)
		{
			foreach(array_keys($_POST) as $key)
			{
				if(strpos($key,$this->field_prefix())===0)
				{
					$newkey = substr($key,strlen($this->field_prefix()));
					$this->values[$newkey] = $array[$key];
				}
			}
			foreach(array_keys($this->values) as $key)
			{
				if($this->values[$key] == 'checked=checked')
				{
					if (!array_key_exists($this->field_prefix().$key,$array))
					{
						$this->values[$key] = '';
					}
				}
			}
			$this->save();
		}
	}
	function prepare($content)
	{
		$content = str_replace('@@field_prefix@@',$this->field_prefix(),$content);
		foreach(array_keys($this->values) as $key)
		{
			$content = str_replace("@@$key@@",$this->values[$key],$content);
		}
		return $content;
	}
}
?>