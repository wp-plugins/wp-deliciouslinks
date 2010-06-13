<?php
class dc_wp_base_1_0_0 extends dc_base_2_4_0 {
	var $args=null;
	function init($args)
	{
		$this->args=$args;
		add_action('init',array($this,'wp_init'));
	}
	function wp_init()
	{
		load_plugin_textdomain($this->domain,PLUGINDIR.DIRECTORY_SEPARATOR.basename($this->library_path[0]).DIRECTORY_SEPARATOR.'languages');
		foreach((array)$this->args as $arg)
		{
			$this->loadClass($arg);
		}
	}
}

?>