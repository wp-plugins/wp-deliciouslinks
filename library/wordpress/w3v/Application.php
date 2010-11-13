<?php
if (! class_exists ( 'b3v_Application' ))
:
	require_once dirname(dirname ( dirname ( __FILE__ ) )) . DIRECTORY_SEPARATOR . 'base/b3v/Application.php';
	class w3v_Application extends b3v_Application
	{

		protected function set_frontcontroller ()
		{
			parent::set_frontcontroller ( w3v_Controller_Front::getInstance ( $this->application () ) );
		}
		
		private $passed_classes = null;
		public function __construct ( $filename = "" ,  $classes = array())
		{
			$this->passed_classes = $classes;
			if (! function_exists ( "wp" ))
			{throw new Exception ( "WordPress has not loaded." );}
			add_action("plugins_loaded",array($this,"setup"));			
			parent::__construct ( $filename );
		}
		public function relative_path ( $uri = null )
		{
			global $current_blog;
			if (null === $uri)
			{
				$uri = $_SERVER ['REQUEST_URI'];
			}
			//$uri = substr ( $uri , strlen ( $current_blog->path ) );
			$uri = substr ( $uri , strlen ( get_option('site_url') ) );
			
			$uri = explode ( '?' , $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri , '/' );
			$uri = '/' . rtrim ( $uri , '/' );
			return $uri;
		}


		public static function WPload ()
		{
			$path = __FILE__;
			while ( ! empty ( $path ) )
			{
				$path = dirname ( $path );
				$file = $path . DIRECTORY_SEPARATOR . 'wp-load.php';
				if (file_exists ( $file ))
				{return $file;}
			}
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
		public function setup()
		{
			load_plugin_textdomain( get_class($this), false, dirname(plugin_basename($this->application()->filename()))."/languages/" );
			
		}
		
		public function preload_classes ( $classes = array() )
		{
			$classes = ( array ) $classes;
			
			array_unshift($classes, 
				 'w3v_Values'   , 'w3v_Table' , 'w3v_Table_Sites' , 'w3v_Table_Site' , 'w3v_Table_SiteMeta' , 'w3v_Table_Posts' , 'w3v_Table_Blogs' , 'w3v_Table_Blog' , 'w3v_Table_Options' , 'w3v_Table_Users' , 'w3v_Table_UserMeta','w3v_View' , 'w3v_Controller_Action_Abstract' , 'w3v_Controller_Action_Action' , 'w3v_Controller_Action_AdminMenu' , 'w3v_Controller_Action_Control' , 'w3v_Controller_Action_Filter' , 'w3v_Controller_Front' , 'w3v_Controller_Dispatcher' 
			);
			foreach($this->passed_classes as $class)
			{
				$classes[] = $class;
			}
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