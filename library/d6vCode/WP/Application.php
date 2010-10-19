<?php
/**
 * base of all wp applications, loads requred classes and has a few plugins. This show be phasesed out and not used
 * @package Library
 * @subpackage WP_Application
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
if (! class_exists ( 'd6vCode_WP_Application' ))
:
	require_once dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'Application.php';
	class d6vCode_WP_Application extends d6vCode_Application
	{

		public function relative_path ( $uri = null )
		{
			global $current_blog;
			if (null === $uri)
			{
				$uri = $_SERVER ['REQUEST_URI'];
			}
			$uri = substr ( $uri , strlen ( $current_blog->path ) );
			$uri = explode ( '?' , $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri , '/' );
			$uri = '/' . rtrim ( $uri , '/' );
			return $uri;
		}

		public function __construct ( $filename = "" , &$view = null )
		{
			if (! function_exists ( "wp" ))
			{throw new Exception ( "WordPress has not loaded." );}
			add_action("plugins_loaded",array($this,"setup"));			
			parent::__construct ( $filename );
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
		public function setup()
		{
			load_plugin_textdomain( get_class($this), false, dirname(plugin_basename($this->application()->filename()))."/languages/" );
			
		}
		public function preload_classes ( $classes = array() )
		{
			$classes = (array)$classes;
			array_unshift($classes,
				 'd6vCode_WP_Application' , 'd6vCode_WP_Values' , 'd6vCode_WP_Mysql'  , 'd6vCode_WP_Image' , 'd6vCode_WP_Table' , 'd6vCode_WP_Sites' , 'd6vCode_WP_Site' , 'd6vCode_WP_SiteMeta' , 'd6vCode_WP_Posts' , 'd6vCode_WP_Blogs' , 'd6vCode_WP_Blog' , 'd6vCode_WP_Options' , 'd6vCode_WP_Users' , 'd6vCode_WP_UserMeta'
		);
			parent::preload_classes ( $classes );
		}
	}




endif;
