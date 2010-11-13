<?php
class b3v_Data_INI extends b3v_Data_Abstract
{
	public function load ()
	{
		$this->value = $this->staticLoad($this->filename);
		return $this;
	}
	public function staticLoad ($file)
	{
		return parse_ini_file($this->findfile($file),true);
	}
}
