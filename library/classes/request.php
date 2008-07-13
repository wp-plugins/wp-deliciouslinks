<?php
class dc_request_1_0_2 {
	function is_request($form_data = null)
	{
		if (is_null($form_data))
		{
			$form_data = $_POST;
		}
		return (count($form_data)>0);
	}
	function get_data($prefix = '',$form_data = null,$strip_empty=false){
		if (is_null($form_data))
		{
			$form_data = $_POST;
		}
		$data = null;
		foreach (array_keys($_POST) as $key)
		{
			if (($strip_empty && $_POST[$key]!="") || !$strip_empty)
			{
				if ($prefix == "" || strpos($key,$prefix) === 0) {
					$orig_key = substr($key,strlen($prefix));
					$key_details = explode('#',$orig_key);
					switch (count($key_details))
					{
						case 1:
							$data[(string)$key_details[0]] = $_POST[$key];
							break;
						case 2:
							$data[(string)$key_details[1]][(string)$key_details[0]] = $_POST[$key];
							break;
						case 3:
							$data[(string)$key_details[2]][(string)$key_details[1]][(string)$key_details[0]] = $_POST[$key];
							break;
					}
				}
			}
		}
		return $data;
	}
}
?>