<?php
/**
 * bas of all user types. 
 * @package Library
 * @subpackage Type_Abstract
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class D5vCode_Type_Abstract extends D5vCode_Base
{
	// has to be abstract as the function is static the __CLASS__ always return the parent class
	abstract public static function is ($object);
	protected $value = null;
	public function value ($value = null)
	{
		if (! is_null($value)) {
			$this->value = $value;
		}
		return $this->value;
	}
	public function __construct ($value = null)
	{
		parent::__construct();
		$this->value($value);
	}
}
