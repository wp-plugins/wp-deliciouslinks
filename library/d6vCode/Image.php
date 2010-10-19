<?php
/**
 * Routines to relted to Images, and location of images.
 * @package Library
 * @subpackage Image
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_Image extends d6vCode_Base
{
	public function twitter ()
	{
		return $this->application()->loader()->find_file('public/Image/twitter-32x32.gif');
	}
	public function facebook ()
	{
		return $this->application()->loader()->find_file('public/Image/facebook-32x32.gif');
	}
}