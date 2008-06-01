<?php
class dc_mail_1_1_0 extends dc_base_2_4_0 {
	var $from ="";
	var $from_name ="";
	var $to ="";
	var $subject ="";
	var $message ="";
	function send()
	{
		$message=$this->loadHTML('mail_message');
		$message=str_replace('@@content@@',$this->message,$message);
		$headers=$this->loadHTML('mail_headers');
		$headers=str_replace('@@from@@',$this->from,$headers);
		$headers=str_replace('@@from_name@@',$this->from_name,$headers);
		mail($this->to,$this->subject,$message,$headers);
	}
}
?>