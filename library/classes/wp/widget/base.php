<?php
class dc_wp_widget_1_2_0 extends dc_base_2_4_0 {
	var $max_widgets = 20;
	var $Description = "";
	function init()
	{
		add_action('widgets_init',array($this,'configfirst'));
		add_action('sidebar_admin_setup', array(&$this,'configfirst'));
	}


	function configfirst()
	{
		if (!is_null($this->callback))
		{
			call_user_func_array($this->callback,array());
		}
	}
	var $widgetDims=null;
	var $Name = '';
	var $Desc = '';
	var $Base = '';
	var $Class = 'DCoda';
	var $widget_callback = null;
	var $option_callback = null;
	var $has_option = null;
	function config($name,$callback,$option_callback = null,$dims = null,$Desc = null)
	{
		$this->Name = $name;
		$this->Base = str_replace('-','',sanitize_title_with_dashes($this->Name));
		if (!is_null($Desc))
		{
			$this->Desc=$Desc;
		}
		if (is_null($dims)) {
			$this->widgetDims = array('width' => 460, 'height' => 65);
		} else {
			$this->widgetDims = array('width' => $dims[0], 'height' => $dims[1]);
		}
			$this->widget_callback = $callback;
		$this->option_callback = $option_callback;
		$this->register();
	}

	function widget( $args, $widget_args = 1 ) {
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
		$o = $this->loadClass('wp_option');
		$title = $o->values[$this->Name][$number]['title'];
		echo $before_widget;
		if ($title!="") {
			echo $before_title;
			echo "$title";
			echo $after_title;
		}
		echo call_user_func_array($this->widget_callback,$number);
		echo $after_widget;
	}
	function control( $widget_args = 1 ) {
		global $wp_registered_widgets;
		static $updated = false;

		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );

		$o = $this->loadClass('wp_options');
		if (!$updated && !empty($_POST['sidebar']) ) {
			$sidebar = (string) $_POST['sidebar'];

			$sidebars_widgets = wp_get_sidebars_widgets();
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();

			foreach ( $this_sidebar as $_widget_id ) {
				if ( !empty($wp_registered_widgets[$_widget_id]['callback']) && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( $this->Base."-$widget_number", $_POST['widget-id'] ) )
						unset($o->values[$this->Name][$widget_number]);
				}
			}

			foreach ( array_keys((array) $_POST[$this->field_prefix()]) as $key ) {
				if(!empty($key))
				{
					if (!empty($o->values[$this->Name]))
						$o->values[$this->Name];
						$o->values[$this->Name][$key]=$_POST[$this->field_prefix()][$key];
				}
			}
			$o->save();
		}

		if ( -1 == $number ) {
			$number = '%i%';
		}

		$title = $o->values[$this->Name][$number]['title'];
		$page = $this->loadHTML('wp_widget_options_template');
		$page = str_replace("@@content@@",call_user_func_array($this->option_callback,$number),$page);
		$page = str_replace("@@prefix@@",$this->field_prefix(),$page);
		$page = str_replace("@@number@@",$number,$page);
		$page = str_replace("@@name@@",$this->Name,$page);
		$page = str_replace("@@title@@",$title,$page);
		echo $page;
	}
	function register() {
		global $wp_registered_controls;
		$opts=$this->loadClass('wp_options');
		$widget_ops = array('classname' => $this->Class, 'description' => $this->Desc,$this->domain);
		$control_ops = array('width' => $this->widgetDims['width'], 'height' => $this->widgetDims['height'], 'id_base' => $this->Base);
		$name = $this->Name;
		$registered = false;
		foreach ( array_keys((array)$opts->values[$this->Name]) as $o ) {
			if (!empty($o))
			{
				$id = $this->Base."-$o";
				wp_register_sidebar_widget( $id, $name, array($this,'widget'), $widget_ops, array( 'number' => $o ) );
				wp_register_widget_control( $id, $name, array($this, 'control'), $control_ops, array( 'number' => $o ) );
				$registered=true;
			}
		}
		if ( !$registered ) {
			wp_register_sidebar_widget( $this->Base.'-1', $name,array($this,'widget'), $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( $this->Base.'-1', $name,array($this, 'control'), $control_ops, array( 'number' => -1 ) );
		}
	}

	function field_prefix()
	{
		return $this->Class.'-'.$this->Base;
	}
}

?>