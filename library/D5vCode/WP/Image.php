<?php
/**
 * find images in the project using wp specific find routines
 * @package Library
 * @subpackage WP_Image
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Image extends D5vCode_Image
{
	public function twitter ()
	{
		return D5vCode_WP_Values::urlFromFileame(parent::twitter());
	}
	public function facebook ()
	{
		return D5vCode_WP_Values::urlFromFileame(parent::facebook());
	}
}