<?php
class dc_array_1_0_0 extends dc_base_2_4_0 {
	function delete_key($array,$delete)
	{
		$return = null;
		foreach(array_keys((array)$array) as $key)
		{
			if ((string)$key != (string)$delete)
			{
				$return[$key] = $array[$key];
			}
		}
		return $return;
	}
	function implode($array)
	{
		$insert = '';
		$return = '';
		foreach (array_keys($array) as $key)
		{
			$return .= $insert;
			$return .= $key."=".$array[$key];
			$insert = "&";
		}
		return $return;
	}
}
?>