<?php
class w3v_View extends b3v_View
{
	public function _e($text)
	{
		_e($text,$this->domain);
	}
	public function __($text)
	{
		return __($text,$this->domain);
	}
	protected $domain = null;
	public function __construct(&$application)
	{
		$this->domain = get_class($application);
		parent::__construct($application);	
	}
}
