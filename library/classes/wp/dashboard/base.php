<?php
class dc_wp_dashboard_1_3_0 extends dc_base_2_4_0 {
// -- DashBoard stuff ----------------------------------------------------------------------------------------------------------------
	function init() {
		add_action('admin_menu', Array(&$this,'init_dashboard'));
	}

	function add_page($page_title,$menu_title,$callback) {
		global $menu;
		$Page = "";
		foreach((array)$menu as $menu_item)
		{
			if ($menu_item[0] == $page_title)
			{
				$Page = $menu_item[2];
				break;
			}

		}
		if ($Page == "")
		{
			$Page = $menu_title;
			add_menu_page($menu_title, $page_title, 8,$Page,$callback);
		}
		add_submenu_page($Page,$page_title,$menu_title,8,$menu_title, $callback);
	}
	function init_dashboard() {
		call_user_func_array($this->callback,array());
	}
	function template($title="",$content="",$options=null,$submit = null,$display=true,$saved=null)
	{
		if(is_null($submit))
		{
			$submit=__('Save Settings',$this->domain);
		}
		if(is_null($saved))
		{
			$saved=__('Settings Saved',$this->domain);
		}
		$return = $this->loadHTML('wp_dashboard_template');
		$return = str_replace('@@title@@',$title,$return);
		$return = str_replace('@@content@@',$content,$return);
		if ($display)
		{
			$return = str_replace('@@display@@',"",$return);
		}
		else
		{
			$return = str_replace('@@display@@'," style='display:none' ",$return);
		}
		if ($_POST)
		{
			$return = str_replace('@@display_saved@@',"",$return);
		}
		else
		{
			$return = str_replace('@@display_saved@@'," style='display:none' ",$return);
		}
		$return = str_replace('@@prefix@@',$this->field_prefix(),$return);
		$return = str_replace('@@submit_options@@',$submit,$return);
		$return = str_replace('@@saved@@',$saved,$return);
			foreach(array_keys((array)$options) as $key)
		{
			$return = str_replace('@@'.$key.'@@',$options[$key],$return);
		}
			echo $return;
	}
	function field_prefix()
	{
		return $this->projectClass.'_';
	}
}
?>