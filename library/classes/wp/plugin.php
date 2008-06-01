<?php
class dc_wp_plugin_1_1_0 extends dc_base_2_4_0 {

	function init() {
		if(!is_null($this->callback))
			add_action('plugins_loaded', $this->callback);
	}
		// 		print_r (get_option('active_plugins'));
	function baseURL($file="")
	{
		//return PLUGINDIR;
		$path=$this->library_path[0];
		$path = str_replace('\\\\','\\',$path);
		$path = str_replace('\\','/',$path);
		$path = substr($path,strpos($path,PLUGINDIR));
		$path = get_option('home').'/'.substr($path,strpos($path,PLUGINDIR));
		return $path.'/'.$file;
	}
	function details($filename = null)
	{
		$file = $this->loadClass('file');
		if(is_null($filename))
		{
			$filename = $this->project_file;
		}
		$pattern = "|(.*):(.*)|i";
		$content = $file->get($filename);
		preg_match_all($pattern,$content,$matches,PREG_SET_ORDER);
		$return = null;
		foreach((array)$matches as $match)
		{
			$key = trim($match[1]);
			$key = strtolower($key);
			$return[$key] = trim($match[2]);
		}
		$return['plugin path'] = $filename;
		return $return;
	}

	function folder()
	{
		return ABSPATH.PLUGINDIR.DIRECTORY_SEPARATOR;
	}
	function plugins()
	{
		foreach((array)get_option('active_plugins') as $plugin)
		{
			print_r($this->details($this->folder().$plugin));
		}
	}
}

?>