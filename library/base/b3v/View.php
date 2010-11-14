<?php
class b3v_View extends b3v_Base
{
	public function selected($value,$check)
	{
		echo $this->checksel($value,$check,'selected');
	}
	public function checked($value,$check)
	{
		echo $this->checksel($value,$check,'checked');
	}
	private function checksel($value,$check,$output)
	{
		$selected = '';
		if(strtolower($value)==strtolower($check))
		{
			$selected= " $output ";
			
		}
		return $selected;
	}
	private $_alternate = false;
	public function alternate($reset = false)
	{
		if($reset)
		{
			$this->_alternate = false;
		}
		$this->_alternate = !$this->_alternate; 
		if($this->_alternate)
		{
			return ' alternate ';
		}
		return '';
	}
	function __ ($value)
	{
		return $value;
	}
	function _e ($value)
	{
		echo $this->__($value);
	}
	public function render ($filename)
	{
		$page = "";
		$paths = $this->application()->frontcontroller()->getViewPaths();
		$dirs = $this->application()->loader()->includepath($paths);
		$fullpath = $this->application()->loader()->find_file($filename, true, $dirs);
		if ($fullpath !== false) {
			ob_start();
			require $fullpath;
			$page = ob_get_contents();
			ob_end_clean();
			return $page;
		}
		return null;
	}
}
