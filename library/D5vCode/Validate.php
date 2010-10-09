<?php
/**
 * A central collection of routines to validate input
 * @package Library
 * @subpackage Valication
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Validate extends D5vCode_Base  {
	public static function Email($string)
	{
		return strpos($string,'@')!==false;
	}
}