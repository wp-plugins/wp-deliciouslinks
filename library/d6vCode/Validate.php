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
class d6vCode_Validate extends d6vCode_Base  {
	public static function Email($string)
	{
		return strpos($string,'@')!==false;
	}
}