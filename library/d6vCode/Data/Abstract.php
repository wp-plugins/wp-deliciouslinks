<?php
/**
 * base of all file data classes
 * @package Library
 * @subpackage Data_Abstract
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class d6vCode_Data_Abstract extends d6vCode_Type_Array
{
	abstract public function staticLoad ($file);
	abstract public function load ();
	protected function findfile ($file)
	{
		return $this->application()->loader()->find_file( $file);
	}
	protected $filename = "";
	public function getArray()
	{
		$return = array();
		foreach($this as $key=>$value)
		{
			$return[$key]=$value;	
		}
		return $return;
	}
	public function __construct ($application, $file, $array = null)
	{
		parent::__construct($array);
		$this->set_application($application);
		$this->filename = $file;
		$this->load();
	}
}
