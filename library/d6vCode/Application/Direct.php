<?php
/**
 * A flavor of an application to be defined when the applicaiton is called direct
 * @package Library
 * @subpackage Applicaiton_Direct
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
if (! class_exists ( 'd6vCode_Application_Direct' ))
:
	require_once dirname ( dirname ( __FILE__ ) ) . '/Application.php';
	class d6vCode_Application_Direct extends d6vCode_Application
	{

		private $_error_controller = null;

		public function error_controller ()
		{
			if (null === $this->_error_controller)
			{
				$this->set_error_controller ();
			}
			return $this->_error_controller;
		}

		public function set_error_controller ( $error_controller = null )
		{
			if (null === $error_controller)
			{
				$this->_error_controller = 'd6vCodeController';
			}
			else
			{
				$this->_error_controller = $error_controller;
			}
		}

		public function __construct ( $filename )
		{
			parent::__construct ( $filename );
			$path = ltrim ( substr ( $raw_uri , strlen ( $root_uri ) ) , '/' );
			$split = explode ( '/' , $this->application ()->page () );
			if (count ( $split ) < 2 || $split [1] == "")
			{
				$act = 'indexController';
			}
			else
			{
				$act = $split [1] . 'Controller';
			}
			$path = $this->loader ()->includepath ( $this->application ()->frontcontroller ()->getControllerPaths () );
			if (false === $this->loader ()->find_file ( $act . '.php' , true , $path ))
			{
				$act = $this->error_controller ();
			}
			$this->loader ()->load_class ( $act , $path );
			$class = new $act ( $this->application () );
			$class->controller ();
		}
	}




endif;