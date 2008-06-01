<?php
class dc_date_1_0_0 extends dc_base_2_4_0 {
	function stumble($date)
	{
		return strtotime($date);
	}
	function delicious($date)
	{
		$timestr = str_replace("Z","",str_replace("T"," ",$date));
		return strtotime($timestr);

	}
	/**
	 * convert internal datetime w3c standard format
	 *
	 * @param string $date, date to convert
	 * @param string $zone, offset for timezon '+hh:mm','-hh:mm' or 'Z' for gmt
	 * @return formatted date
	 */
	function toW3c($date,$zone="Z")
	{
		return date('Y-m-d',$date).'T'.date('H:i:s',$date).'.00'.$zone;
	}
	function digg($date)
	{
	}
}
?>