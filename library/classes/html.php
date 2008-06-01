<?php
class dc_html_1_1_0 extends dc_base_2_4_0 {
	function init() {
		$this->folders[] = 'pages';
	}
	function load($load) {
		$f = $this->loadClass('file');
		$load=str_replace('_','/',$load);
		$php = false;
		$filename = $f->findFile($load.".html");
		if (is_null($filename)) {
			$filename = $f->findFile($load."/template.html");
		}
		if (is_null($filename)) {
			$filename = $f->findFile($load.".html.php");
			$php=true;
		}
		if (is_null($filename)) {
			$filename = $f->findFile($load."/template.html.php");
			$php=true;
		}
		if (!is_null($filename)) {
			$file = $this->loadClass('file');
			$return = $file->get($filename);
			if ($php)
			{
				$out=$return;
				ob_start();
				eval("?>".$out);
				$return = ob_get_contents();
				ob_end_clean();
			}
			return $return;
		}
		return null;
	}
}
?>