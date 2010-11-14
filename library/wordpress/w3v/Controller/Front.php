<?php
class w3v_Controller_Front extends b3v_Controller_Front
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
		parent::setDispatcher(new w3v_Controller_Dispatcher($this->application()));
	}
}
