<?php
class dc_wp_file_1_0_0 extends dc_base_2_4_0 {
	function baseURL($file=null)
	{
		//return PLUGINDIR;
		if(is_null($file))
		{
			$path=$this->library_path[0];
		} else {
			$path=$file;
		}
		$path = str_replace('\\\\','\\',$path);
		$path = str_replace('\\','/',$path);
		$path = substr($path,strpos($path,PLUGINDIR));
		$path = get_option('home').'/'.substr($path,strpos($path,PLUGINDIR));
		return $path;
	}
}

?>