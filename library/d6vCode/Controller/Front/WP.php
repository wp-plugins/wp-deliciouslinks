<?php
/**
 * singleton class attaches the dispacher to the view. Not properly finished at the moment
 * @package Library
 * @subpackage Controller_Front_WP
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_Controller_Front_WP extends d6vCode_Controller_Front
{
	protected function setControllerPaths ()
	{
		parent::setControllerPaths();
		$this->controllerPaths[] = 'Controllers/WP/';
	}
	protected function setViewPaths ()
	{
		parent::setViewPaths();
		$this->viewPaths[] = 'Views/WP/';
	}
	public static function getInstance ($application)
	{
		$filename = $application->filename();
		if (!array_key_exists($filename, self::$_instance)) {
			self::$_instance[$filename] = new self($application);
			self::$_instance[$filename]->setup();
		}
		return self::$_instance[$filename];
	}
	protected function setDispatcher ()
	{
		parent::setDispatcher(new d6vCode_Controller_Dispatcher_WP($this->application()));
	}
}
