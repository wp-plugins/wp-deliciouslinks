<?php
/**
 * a sub class for mail routines to pass mail through wp mail routines to allow wp to do its stuff to the mails.
 * @package Library
 * @subpackage WP_Mail
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_WP_Mail extends d6vCode_Mail
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