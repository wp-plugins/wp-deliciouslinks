<?php
if (!class_exists('dc_base_2_4_0')) {
/*
	note:
	major limitation with php 4.3.
	classes loaded into class variable in constructor
	are not retained accros a call back to function even
	within the same class. avoid using them unless you are
	sure you will be running on php 5
*/

	class dc_base_2_4_0 {
		// the top most class in the class stucture
		var $projectClass = null;
		// the path to be searched when looking for files
		var $library_path = array();
		// sub folders to search in each of the above
		var $folders = array();
		// the callback function that may be called should you need it
		var $callback = null;
		var $domain="";
		function dc_base_2_4_0($projectClass = null,$callback=null,$args=array(),$caller=null){
			global $dcoda_direct;
			if(is_null($caller))
				$caller=$this;
			extract( (array)$args, EXTR_SKIP );

			$this->callback = $callback;

			if (is_null($projectClass)) {
				$this->projectClass = get_class($this);
			} else {
				$this->projectClass = $projectClass;
			}
			$this->setPath(dirname(__FILE__));
			$this->library_path = $caller->library_path;
			$this->domain = $caller->domain;

			$this->folders[] = "classes";
			// check for direct calls
			$file = str_replace('\\','/',__FILE__);
			$file = str_replace('//','/',$file);
			$script = str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']);
			$script = str_replace('//','/',$script);
			$script = dirname($script);
			$file = dirname(dirname(dirname($file)));
			if ( $script == $file && !$dcoda_direct)
			{
				$dcoda_direct = true;
				$this->direct();
			}
			else
			{
				$this->legacy($args);
				$this->init($args);
			}
		}

		function setPath($file)
		{
			$this->library_path=array();
			$library_path = dirname($file);
			$this->domain=basename($file,".php");
			switch(basename($library_path)){
				case 'classes':
				case 'pages':
					$library_path = dirname($library_path);
			}
			switch(basename($library_path)){
				case 'library':
					$library_path = dirname($library_path);
			}
			$this->library_path[]=$library_path;
			$this->library_path[]=$library_path.DIRECTORY_SEPARATOR.'library';
		}

		function direct()
		{
			header("HTTP/1.1 301 Moved Permanently");
			header ("Location: http://www.dcoda.co.uk");
		}

		// change to do upgrade and other stuff before the code kicks off propper
		function legacy()
		{

		}
		// first proper code to execute after initialisation
		function init()
		{
		}

		function loadClass($load,$callback=null,$args=array())
		{
			$load=str_replace('_','/',$load);
			$file=dirname(__FILE__).DIRECTORY_SEPARATOR.$load.".php";
			if ($load!='file')
			{
				$f=$this->loadClass('file');
				$file = $f->findFile($load.".php");
				if (is_null($file)) {
					$file = $f->findFile($load.DIRECTORY_SEPARATOR."base.php");
				}
			}
			if (!is_null($file)) {
				$cname = $this->_getClassname($file);
				if (!class_exists($cname))
				{
					require($file);
				}
				$return = new $cname($this->projectClass,$callback,$args,$this);
				return $return;
			}
			return null;
		}
		function debug($var=null,$echo=true,$name='Call to test',$condition=true)
		{
			$t = $this->loadClass('debug');
			return $t->test($var,$echo,$name,$condition);
		}
		function loadHTML($load)
		{
			$h = $this->loadClass('html');
			return $h->load($load);
		}
		function _getClassname($file)
		{
			$handle = fopen($file, "rb");
			$top = fread($handle, 256);
			fclose($handle);
			$pattern ='|class\s*(.*[^\W])\W|Ui';
			preg_match_all($pattern,$top,$matches,PREG_SET_ORDER);
			return trim($matches[0][1]);
		}
	}
}
?>