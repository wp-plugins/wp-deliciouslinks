<?php
/**
 * the basis of all wp plugins
 * @package Library
 * @subpackage WP_Plugin
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
if (! class_exists ( 'd6vCode_WP_Plugin' ))
:
	require_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'Application.php';
	class d6vCode_WP_Plugin extends d6vCode_WP_Application
	{

		protected function set_frontcontroller ()
		{
			parent::set_frontcontroller ( d6vCode_Controller_Front_WP::getInstance ( $this->application () ) );
		}

		protected function set_loader ()
		{
			parent::set_loader ();
			$this->loader ()->remove_subfolder ( 'application' );
			$this->loader ()->add_subfolder ( 'plugin' );
			$this->loader ()->add_subfolder ( 'plugin/Models' );
		}

		public function __construct ( $filename )
		{
			parent::__construct ( $filename );
		}
		private static $templateDirBase = null;

		private static function templateDirBase ()
		{
			if (is_null ( self::$templateDirBase ))
			{
				self::$templateDirBase = dirname ( dirname ( dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) );
			}
			return self::$templateDirBase;
		}
		private static $templateDir = null;

		public static function templateDir ( $subfolder = null )
		{
			if (! is_null ( $subfolder ) || is_null ( self::$templateDir ))
			{
				self::$templateDir = self::templateDirBase () . DIRECTORY_SEPARATOR . $subfolder;
			}
			return self::$templateDir;
		}

		public function preload_classes ( $classes = array() )
		{
			$classes = ( array ) $classes;
			array_unshift($classes, 
				'd6vCode_WP_View' , 'd6vCode_Controller_Action_WP_Abstract' , 'd6vCode_Controller_Action_WP_Action' , 'd6vCode_Controller_Action_WP_AdminMenu' , 'd6vCode_Controller_Action_WP_Control' , 'd6vCode_Controller_Action_WP_Filter' , 'd6vCode_Controller_Front_WP' , 'd6vCode_Controller_Dispatcher_WP' 
			);

			parent::preload_classes ( $classes );
		}

		//--- MeetSpec
		public function showError ()
		{
			add_action ( 'init' , array ( 
				$this , 'errorInit' 
			) );
		}

		public function errorInit ()
		{
			add_action ( 'admin_notices' , array ( 
				$this , 'errorNotice' 
			) );
		}

		public function errorNotice ()
		{
			foreach ( ( array ) $this->errors as $errors )
			{
				echo "
				<div class='updated fade'><p>" . sprintf ( $errors , $this->get_name () ) . "</p></div>
				";
			}
		}
	}





endif;