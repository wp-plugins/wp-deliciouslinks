<?php
class dc_wptimer_1_1_1 extends dc_base_2_4_0 {
	var $startTime = 0;
	function timeout($start=false)
	{
		$o=$this->loadClass('wp_options');
		if($start)
		{
			$this->startTime = microtime(true);
		}
		return ((microtime(true)-$this->startTime)>$o->values['timeout']);
	}
	function checkTimer($timeout,$callback=array())
	{
		$o=$this->loadClass('wp_options');
		if ($o->values['Timer'] == '' || ($o->values['Timer']+$timeout)<time())
		{
			$o->values['Timer'] = time();
			$o->save();
			call_user_func($callback);
		}
	}
}
?>
