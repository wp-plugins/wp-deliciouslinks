<?php
if (! class_exists ( 'b7v_Application' )) :
	require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'base/b7v/Application.php';
	class w7v_Application extends b7v_Application {
		protected function set_frontcontroller() {
			parent::set_frontcontroller ( w7v_Controller_Front::getInstance ( $this->application () ) );
		}
		protected $passed_classes = null;
		public function __construct($filename = "", $classes = array()) {
			$this->passed_classes = $classes;
			if (! function_exists ( "wp" )) {
				throw new Exception ( "WordPress has not loaded." );
			}
			add_action ( "plugins_loaded", array ($this, "setup" ) );
			parent::__construct ( $filename );
			$this->info = new w7v_info ( $this );
		}
		public function relative_path($uri = null) {
			global $current_blog;
			if (null === $uri) {
				$uri = $_SERVER ['REQUEST_URI'];
			}
			//$uri = substr ( $uri , strlen ( $current_blog->path ) );
			$uri = substr ( $uri, strlen ( get_option ( 'site_url' ) ) );
			
			$uri = explode ( '?', $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri, '/' );
			$uri = '/' . rtrim ( $uri, '/' );
			return $uri;
		}
		private static $templateDirBase = null;
		private static function templateDirBase() {
			if (is_null ( self::$templateDirBase )) {
				self::$templateDirBase = dirname ( dirname ( dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) );
			}
			return self::$templateDirBase;
		}
		private static $templateDir = null;
		public static function templateDir($subfolder = null) {
			if (! is_null ( $subfolder ) || is_null ( self::$templateDir )) {
				self::$templateDir = self::templateDirBase () . DIRECTORY_SEPARATOR . $subfolder;
			}
			return self::$templateDir;
		}
		public function setup() {
			load_plugin_textdomain ( get_class ( $this ), false, dirname ( plugin_basename ( $this->application ()->filename () ) ) . "/languages/" );
		}
		public function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			array_unshift ( $classes, 'w7v_Info', 'w7v_Values', 'w7v_Table','w7v_Table_SiteMeta', 'w7v_Table_Sites', 'w7v_Table_Site', 'w7v_Table_Posts', 'w7v_Table_Blogs', 'w7v_Table_Blog', 'w7v_Table_Options', 'w7v_Table_Users', 'w7v_Table_UserMeta', 'w7v_View', 'w7v_Controller_Action_Abstract', 'w7v_Controller_Action_Action', 'w7v_Controller_Action_AdminMenu', 'w7v_Controller_Action_Control', 'w7v_Controller_Action_Filter', 'w7v_Controller_Front', 'w7v_Controller_Dispatcher', 'w7v_Table_Comments' );
			foreach ( $this->passed_classes as $class ) {
				$classes [] = $class;
			}
			parent::preload_classes ( $classes );
		}
		private $info = null;
		public function info() {
			return $this->info;
		}
	}

endif;