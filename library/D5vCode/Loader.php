<?php
/**
 * Based on the priciple of the Zend_loader. Allows files to be loaded realtive the the application path, and checking for existance etc
 * @package Library
 * @subpackage Loader
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Loader extends D5vCode_base
{
	private $_includepath = null;

	private function sanitize_path ( $path )
	{
		return rtrim ( $path , DIRECTORY_SEPARATOR );
	}

	public function includepath ( $folders = null )
	{
		if (null === $this->_includepath)
		{
			$this->set_includepath ();
		}
		if (null !== $folders)
		{
			$dirs = array ();
			foreach ( $this->_includepath as $path )
			{
				foreach ( ( array ) $folders as $folder )
				{
					$newfolder = $path . DIRECTORY_SEPARATOR . $this->sanitize_path ( $folder );
					if (is_dir ( $newfolder ))
					{
						$dirs [$newfolder] = $newfolder;
					}
				}
			}
			return $dirs;
		}
		return $this->_includepath;
	}

	private function set_includepath ()
	{
		$this->_includepath = array ();
		$path = $this->sanitize_path ( dirname ( $this->application ()->filename () ) );
		$root = dirname($path);
		$this->_includepath [$root] = $root;
		$this->_includepath [$path] = $path;
		foreach ( $this->subfolders () as $subfolder )
		{
			$newfolder = $path . DIRECTORY_SEPARATOR . $subfolder;
			if (is_dir ( $newfolder ))
			{
				$this->_includepath [$newfolder] = $newfolder;
			}
		};
	}

	private function reset_includepath ()
	{
		$this->_includepath = null;
	}
	private $_subfolders = null;

	public function subfolders ()
	{
		if (null === $this->_subfolders)
		{
			$this->set_subfolders ();
		}
		return $this->_subfolders;
	}

	private function set_subfolders ()
	{
		$this->_subfolders = array ();
		$this->add_subfolder ( 'library' );
		$this->add_subfolder ( 'library/Models' );
	}

	public function add_subfolder ( $folder )
	{
		$folder = $this->sanitize_path ( $folder );
		if (null === $this->_subfolders)
		{
			$this->set_subfolders ();
		}
		$this->_subfolders [$folder] = $folder;
		$this->reset_includepath ();
	}

	public function remove_subfolder ( $folder )
	{
		if (array_key_exists ( $folder , $this->_subfolders ))
		{
			unset ( $this->_subfolders [$folder] );
			$this->reset_includepath ();
		}
	}

	public function __construct ( $application )
	{
		parent::__construct ( $application );
	}

	public function load_class ( $class , $include_path = null )
	{
		if (null === $include_path)
		{
			$include_path = $this->includepath ();
		}
		return self::_loadClass ( $class , $include_path );
	}

	public function find_file ( $filename , $quiet = false , $include_path = null )
	{
		self::securityCheck ( $filename );
		if (null === $include_path)
		{
			$include_path = $this->includepath ();
		}
		if (file_exists ( $filename ))
		{return $filename;}
		foreach ( $include_path as $dir )
		{
			if (file_exists ( $dir . DIRECTORY_SEPARATOR . $filename ))
			{return $dir . DIRECTORY_SEPARATOR . $filename;}
		}
		if (! $quiet)
		{throw new Exception ( $filename . ' Not Found ' . print_r ( $include_path , true ) );}
		return false;
	}

	private static function securityCheck ( $filename )
	{
		if (preg_match ( '/[^a-z0-9\\/\\\\_.:-]/i' , $filename ))
		{throw new Exception ( 'Security check: Illegal character in filename' );}
	}

	public function file ( $filename )
	{
		$filename = $this->find_file ( $filename );
		return file_get_contents ( $filename );
	}
	/*
	 * roots are require to tell the top level of an application in a multi environemt
	 */
	private static $roots = array ();

	public static function roots ()
	{
		return self::$roots;
	}

	public function addRoot ( $file )
	{
		$path = dirname ( $file );
		if (strrpos ( $path , 'library/bootstraps' ) !== false)
		{
			$path = dirname ( dirname ( $path ) );
		}
		self::$roots [$file] = $path;
		self::$_include_path = null;
	}

	// due to possible multi envirnment wchi means the class may have been loaded by another plugin
	// find use the location of the first file than is not the current file
	private static function getFilename ()
	{
		$file = __FILE__;
		foreach ( debug_backtrace () as $call )
		{
			if (__FILE__ != $call ['file'])
			{
				$file = $call ['file'];
				break;
			}
		}
		// remeber realfile returns false if file does not exist
		return $file;
	}
	private static $_include_path = null;

	public static function getDirs ( $subdirs = null )
	{
		if (null === self::$_include_path)
		{
			$top = array ( 
				'library' , 'library/applet' , 'plugin' , 'plugin/Models' , 'applet' , 'application' 
			);
			self::$_include_path = array ();
			$first = true;
			foreach ( self::roots () as $root )
			{
				foreach ( $top as $t )
				{
					if ($first || strpos ( $t , 'library' ) !== 0)
					{
						$dir = $root . DIRECTORY_SEPARATOR . $t . DIRECTORY_SEPARATOR;
						if (is_dir ( $dir ))
						{
							self::$_include_path [] = $dir;
						}
					}
				}
				$first = false;
			}
		}
		if (null !== $subdirs)
		{
			$dirs = array ();
			foreach ( self::$_include_path as $dir )
			{
				foreach ( ( array ) $subdirs as $sdir )
				{
					if (is_dir ( $dir . $sdir ))
					{
						$dirs [] = $dir . $sdir;
					}
				}
			}
			return $dirs;
		}
		return self::$_include_path;
	}

	public static function loadFile ( $filename , $dirs = null , $once = false )
	{
		if (is_null ( $dirs ))
		{
			$dirs = self::getDirs ();
		}
		return self::_loadFile ( $filename , $dirs , $once );
	}

	public static function getFile ( $filename )
	{
		$filename = Zend_Loader::findfile ( $filename );
		return file_get_contents ( $filename );
	}

	public static function findfile ( $filename , $quiet = false , $dirs = null )
	{
		self::securityCheck ( $filename );
		if (is_null ( $dirs ))
		{
			$dirs = self::getDirs ();
		}
		if (file_exists ( $filename ))
		{return $filename;}
		foreach ( $dirs as $dir )
		{
			if (file_exists ( $dir . $filename ))
			{return $dir . $filename;}
		}
		if (! $quiet)
		{throw new Exception ( $filename . ' Not Found ' . print_r ( $dirs , true ) );}
		return false;
	}
		public static function _loadClass($class, $dirs = null)
	{
		if (class_exists($class, false) || interface_exists($class, false)) {
			return;
		}

		if ((null !== $dirs) && !is_string($dirs) && !is_array($dirs)) {
			throw new Exception('Directory argument must be a string or an array');
		}

		// Autodiscover the path from the class name
		// Implementation is PHP namespace-aware, and based on
		// Framework Interop Group reference implementation:
		// http://groups.google.com/group/php-standards/web/psr-0-final-proposal
		$className = ltrim($class, '\\');
		$file      = '';
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$file      = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$file .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		if (!empty($dirs)) {
			// use the autodiscovered path
			$dirPath = dirname($file);
			if (is_string($dirs)) {
				$dirs = explode(PATH_SEPARATOR, $dirs);
			}
			foreach ($dirs as $key => $dir) {
				if ($dir == '.') {
					$dirs[$key] = $dirPath;
				} else {
					$dir = rtrim($dir, '\\/');
					$dirs[$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
				}
			}
			$file = basename($file);
			self::loadFile($file, $dirs, true);
		} else {
			self::loadFile($file, null, true);
		}

		if (!class_exists($class, false) && !interface_exists($class, false)) {
			throw new Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
		}
	}
	public static function _loadFile($filename, $dirs = null, $once = false)
	{
		self::securityCheck($filename);

		/**
		 * Search in provided directories, as well as include_path
		 */
		$incPath = false;
		if (!empty($dirs) && (is_array($dirs) || is_string($dirs))) {
			if (is_array($dirs)) {
				$dirs = implode(PATH_SEPARATOR, $dirs);
			}
			$incPath = get_include_path();
			set_include_path($dirs . PATH_SEPARATOR . $incPath);
		}

		/**
		 * Try finding for the plain filename in the include_path.
		 */
		if ($once) {
			include_once $filename;
		} else {
			include $filename;
		}

		/**
		 * If searching in directories, reset include_path
		 */
		if ($incPath) {
			set_include_path($incPath);
		}

		return true;
	}
	
}

