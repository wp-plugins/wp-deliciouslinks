<?php
/**
 * Based on the idea of the Zend fron controller. Thisis the inerface between the applcaiotn and the controllers. This keeps track f the location of controllers
 * @package Library
 * @subpackage Controller_Front
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Controller_Front extends D5vCode_Base
{
	protected $controllerPaths = array();
	protected function setControllerPaths ()
	{
		$this->controllerPaths[] = 'Controllers/';
	}
	protected $viewPaths = array();
	protected function setViewPaths ()
	{
		$this->viewPaths[] = basename(dirname(dirname(dirname(dirname(__FILE__))))).'_custom';
		$this->viewPaths[] = 'Views/';
	}
	public function getControllerPaths ()
	{
		return $this->controllerPaths;
	}
	public function getViewPaths ()
	{
		return $this->viewPaths;
	}
	//---
	protected $_dispatcher = null;
	public function getDispatcher ()
	{
		$this->setDispatcher();
		return $this->_dispatcher;
	}
	protected static $_instance = array();
	public static function getInstance ($application)
	{
		$filename = $application->filename();
		if (! array_key_exists($filename, self::$_instance)) {
			self::$_instance[$filename] = new self($application);
			self::$_instance[$filename]->setup();
		}
		return self::$_instance[$filename];
	}
	protected function setDispatcher ($dispatcher = null)
	{
		if (null === $this->_dispatcher) {
			if (null === $dispatcher) {
				$this->_dispatcher = new D5vCode_Controller_Dispatcher_Standard($this->application());
			} else {
				$this->_dispatcher = $dispatcher;
			}
		}
	}
	public function setup ()
	{
		$this->setControllerPaths();
		$this->setViewPaths();
		$this->setDispatcher();
	}
	public function __construct ($application)
	{
		parent::__construct($application);
	}
}
