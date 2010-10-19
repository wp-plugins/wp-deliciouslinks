<?php
/**
 * Based on the idea of a Zend_View, A class that is used to do all the output 
 * @package Library
 * @subpackage View
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_View extends d6vCode_Base
{
	public function selected($value,$check)
	{
		$selected = '';
		if(strtolower($value)==strtolower($check))
		{
			$selected= ' selected ';
			
		}
		echo $selected;
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
	private $_image = null;
	public function image ()
	{
		if (null === $this->_image) {
			$this->set_image();
		}
		return $this->_image;
	}
	protected function set_image ($image = null)
	{
		if (null === $image) {
			$this->_image = new d6vCode_Image($this->application());
		} else {
			$this->_image = $image;
		}
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
