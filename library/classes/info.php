<?php
class dc_info_1_0_0 extends dc_base_2_4_0 {
	function ip()
	{
		$return="";
		if (isset($_SERVER))
		{
 			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
 			{
	  			$return = $_SERVER["HTTP_X_FORWARDED_FOR"];
 			}
 			elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
 			{
	  			$return = $_SERVER["HTTP_CLIENT_IP"];
 			}
 			else
 			{
	 			$return = $_SERVER["REMOTE_ADDR"];
 			}
		}
		else
		{
	 		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
 			{
	  			$return = getenv( 'HTTP_X_FORWARDED_FOR' );
 			}
 			elseif ( getenv( 'HTTP_CLIENT_IP' ) )
 			{
	  			$return = getenv( 'HTTP_CLIENT_IP' );
 			}
	 		else
 			{
		  		$return = getenv( 'REMOTE_ADDR' );
 			}
		}
		return $return;
	}
}
?>