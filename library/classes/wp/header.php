<?php
class dc_wp_header_1_4_0 extends dc_base_2_4_0 {
	function config($blog=true,$login=false,$admin=false)
	{
		if (!is_null($this->callback))
		{
			if ($blog)
			{
				add_action('wp_head', $this->callback);
			}
			if ($login)
			{
				add_action('login_head', $this->callback);
			}
			if ($admin)
			{
				add_action('admin_head', $this->callback);
			}
		}
	}
	var $style=array();
	function addStyle($load)
	{
		$load=str_replace('_','/',$load);
		$file=dirname(__FILE__).DIRECTORY_SEPARATOR.$load.".css";
		if (!file_exists($file))
		{
			$f=$this->loadClass('file');
			$f->folders[]='styles';
			$file = $f->findFile($load.".css");
			if (is_null($file)) {
				$file = $f->findFile($load.DIRECTORY_SEPARATOR."style.css");
			}
		}
		if (!is_null($file)) {
			$this->style[]=$file;
			add_action('wp_head', array($this,'styleHeader'));
			return null;
		}
		$file=dirname(__FILE__).DIRECTORY_SEPARATOR.$load.".css.php";
		if (!file_exists($file))
		{
			$f=$this->loadClass('file');
			$f->folders[]='styles';
			$file = $f->findFile($load.".css.php");
			if (is_null($file)) {
				$file = $f->findFile($load.DIRECTORY_SEPARATOR."style.css.php");
			}
		}
		if (!is_null($file)) {
			$this->style[]=$file;
			add_action('wp_head', array($this,'styleHeader'));
		}
		return null;
	}
	function styleHeader($args)
	{
		foreach ((array)$this->style as $style)
		{
			$f=$this->loadClass('wp_file');
		?>
		<link rel="stylesheet" href="<?php echo $f->baseURL($style); ?>" type="text/css" media="screen" />
		<?php
		}
	}
	var $script=array();
	function addScript($load)
	{
		$load=str_replace('_','/',$load);
		$file=dirname(__FILE__).DIRECTORY_SEPARATOR.$load.".js";
		if (!file_exists($file))
		{
			$f=$this->loadClass('file');
			$f->folders[]='scripts';
			$file = $f->findFile($load.".js");
			if (is_null($file)) {
				$file = $f->findFile($load.DIRECTORY_SEPARATOR."script.js");
			}
		}
		if (!is_null($file)) {
			$this->script[]=$file;
			add_action('wp_head', array($this,'scriptHeader'));
			return null;
		}
		$file=dirname(__FILE__).DIRECTORY_SEPARATOR.$load.".js.php";
		if (!file_exists($file))
		{
			$f=$this->loadClass('file');
			$f->folders[]='scripts';
			$file = $f->findFile($load.".js.php");
			if (is_null($file)) {
				$file = $f->findFile($load.DIRECTORY_SEPARATOR."script.js.php");
			}
		}
		if (!is_null($file)) {
			$this->script[]=$file;
			add_action('wp_head', array($this,'scriptHeader'));
		}
		return null;
	}
	function scriptHeader($args)
	{
		foreach((array)$this->script as $script)
		{
			$f=$this->loadClass('wp_file');
		?>
		<script src="<?php echo $f->baseURL($script); ?>"></script>
		<?php
		}
	}


	var $adminHeader="";
	var $loginHeader="";
	var $blogHeader="";
	function addHeader($code="",$blog=true,$login=false,$admin=false)
	{
		if ($blog)
		{
			$this->blogHeader.=$code;
			add_action('wp_head', array($this,'bHeader'));
		}
		if ($login)
		{
			$this->loginHeader.=$code;
			add_action('login_head', array($this,'lHeader'));
		}
		if ($admin)
		{
			$this->adminHeaderHeader.=$code;
			add_action('admin_head', array($this,'aHeader'));
		}
	}
	function bHeader()
	{
			echo $this->blogHeader;
	}
	function lHeader()
	{
			echo $this->loginHeader;
	}
	function aHeader()
	{
			echo $this->adminHeader;
	}

}
?>