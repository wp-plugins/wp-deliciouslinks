<?php
/**
 * modifies the standard view class to point to WP specific funcitons
 * @package Library
 * @subpackage WP_View
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_View extends D5vCode_View
{
	protected function set_image()
	{
		parent::set_image(new D5vCode_WP_Image($this->application()));
	}
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
