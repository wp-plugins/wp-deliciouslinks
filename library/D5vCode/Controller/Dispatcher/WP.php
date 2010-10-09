<?php
/**
 * costomise the set up of controllers for WP
 * @package Library
 * @subpackage Controller_Dispatcher_WP
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Controller_Dispatcher_WP extends D5vCode_Controller_Dispatcher_Standard
{

	public function __construct ( $application )
	{
		parent::__construct ( $application );
		$this->setup ( true );
		add_action ( 'admin_menu' , array ( 
			$this , 'setup' 
		) );
	}
	protected $_controllers = null;

	public function controllers ()
	{
		$this->_controllers = array ();
		$paths = $this->application ()->frontcontroller ()->getControllerPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths );
		foreach ( $dirs as $dir )
		{
			$fs = new D5vCode_FS ( $this->application () , $dir );
			$fs_controllers = $fs->dir ( '*Controller.php' );
			foreach ( $fs_controllers as $fs_controller )
			{
				$this->_controllers [] = $fs_controller;
			}
		}
		return $this->_controllers;
	}

	public function setup ( $notmenu = false )
	{
		$paths = $this->application ()->frontcontroller ()->getControllerPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths );
		foreach ( $this->controllers () as $controller )
		{
			$class = basename ( $controller , ".php" );
			$this->application ()->loader ()->load_class ( $class , $dirs );
			$controllerClass = new $class ( $this->application () );
			if ($notmenu)
			{
				switch ($controllerClass->getType ())
				{
					case D5vCode_Controller_Action_WP_Abstract::WP_FILTER :
					case D5vCode_Controller_Action_WP_Abstract::WP_ACTION :
					case D5vCode_Controller_Action_WP_Abstract::WP_CONTROL :
						$controllerClass->setup ();
				}
			}
			else
			{
				if ($controllerClass->getType () == D5vCode_Controller_Action_WP_Abstract::WP_DASHBOARD)
				{
					$controllerClass->setup ();
				}
			}
		}
	}
}
