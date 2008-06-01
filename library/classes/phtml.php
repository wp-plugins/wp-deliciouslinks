<?php
$_dc_class_name = 'dc_phtml_1_0_0';
if (!class_exists($_dc_class_name)) {
	require_once(dirname(dirname(__FILE__)).'/base.php');
	class dc_phtml_1_0_0 extends dc_base_1_0_1 {
		function init() {
			$this->folders[] = 'pages';
		}
		function load($load) {
			$php = true;
			$filename = $this->find_file($load.".phtml");
			if (is_null($file)) {
				$filename = $this->find_file($load.".html");
				$php = false;
			}
			if (!is_null($filename)) {
				$file = $this->load_class('file');
				$return = $file->get($filename);
				if ($php)
				{
 					ob_start();
 					eval("?>".$return);
 					$return = ob_get_contents();
					ob_end_clean();
					return $return;
				} 
				return $return;
			}
			return null;
		}		
	}
}
?>