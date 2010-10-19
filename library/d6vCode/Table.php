<?php
/**
 * The basis model to access data tables
 * @package Library
 * @subpackage Table
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_Table extends d6vCode_Base
{
	private $_db = null;
	protected function db()
	{
		return $this->_db;
	}
	protected function set_db($db = null)
	{
		if(null === $db)
		{
			$db = d6vCode_Mysql::instance();
		}
		$this->_db = $db;
	}
	private $_name = null;
	protected function name()
	{
		return $this->prefix().$this->_name;
	}
	protected function set_name($name=null)
	{
		if(null == $name)
		{
			$name = 'test';
		}
		$this->_name = $name;
	}
	public function __construct()
	{
		$this->set_db();
		parent::__construct();
	}
/*	public function insert ($data)
	{
		$sql = "INSERT INTO `" . $this->table_name() . "`\n";
		$comma = "";
		$fields = array();
		$values = array();
		foreach ((array) $data as $key => $value) {
			$fields[] = "`" . $key . "`";
			$values[] = "'" . addslashes($value) . "'";
		}
		$fields = implode(',', $fields);
		$values = implode(',', $values);
		$sql .= "(" . $fields . ")\n";
		$sql .= "values (" . $values . ")\n";
		$this->get_results($sql);
	}
	public function select ()
	{
		$sql = "SELECT * FROM `" . $this->table_name() . "`\n";
		return $this->get_results($sql);
	}
	protected function create ()
	{
		if (! is_null($this->fields)) {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $this->table_name() . "` (\n";
			$comma = "";
			foreach ((array) $this->fields as $field_name => $field_def) {
				$sql .= $comma;
				$sql .= "`" . $field_name . "` " . $field_def['type'];
				if (array_key_exists('null', $field_def) && ! $field_def['null']) {
					$sql .= " NOT NULL ";
				}
				if (array_key_exists('extra', $field_def)) {
					$sql .= " " . $field_def['extra'];
				}
				$comma = ",\n";
			}
			if (array_key_exists('primary', $this->keys)) {
				$sql .= $comma . " PRIMARY KEY (`" . $this->keys['primary'] . "`)";
			}
			$sql .= ")";
			//echo $sql;
			$this->get_results($sql);
		}
	}
*/
}