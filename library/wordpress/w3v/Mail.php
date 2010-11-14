<?php
class w3v_Mail extends b3v_Mail
{
	//--- headers
	protected function headercharset()
	{
		return "charset=\"" . get_option('blog_charset') . "\"\n";
	}
	//---
	protected function sendit($to, $subject, $message, $headers="")
	{
		wp_mail($to, $subject, $message, $headers);
	}
}