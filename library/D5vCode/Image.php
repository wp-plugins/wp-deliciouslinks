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
class D5vCode_Image extends D5vCode_Base
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