<?php
class dc_form_1_3_0 {
	function checked($value)
	{
		if (isset($value))
		{
			return " checked = checked ";
		}
		return "";
	}
	function encode_array($array)
	{
		return base64_encode(htmlentities(serialize($array)));
	}
	function decode_array($string)
	{
		$array=base64_decode($string);
		$array=html_entity_decode($array);
		$array=stripcslashes($array);
		$array=unserialize($array);
		return $array;
	}
}
?>