<?php
class b3v_Table extends b3v_Base
{
	protected static $_instance = null;
	public function instance()
	{
		if(null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function execute($sql)
	{
		throw new exception ('Not implamented yet.');
	}
	public function like(&$like)
	{
		$return = "";
		if(null!==$like)
		{
			$return = " LIKE '%s'";
			$return = sprintf($return,$like);
		}
		$like = $return;
	}
	public function from(&$from)
	{
		$return = "";
		if(null!==$from)
		{
			$this->obj_name($from);
			$return = " FROM %s ";
			$return = sprintf($return,$from);
		}
		$from = $return;
	}
	public function obj_name(&$obj)
	{
		$return = '`%s`';
		$return = sprintf($return, $obj);
		$obj = $return;
	}
	public function show_tables($like=null)
	{
		$this->like($like);
		$sql = "SHOW TABLES%s;";
		$sql = sprintf($sql,$like);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_databases($like=null)
	{
		$this->like($like);
		$sql = "SHOW DATABASES%s;";
		$sql = sprintf($sql,$like);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_columns($from,$like = null)
	{
		$this->from($from);
		$this->like($like);
		$sql = "SHOW COLUMNS%s%s;";
		$sql = sprintf($sql,$from,$like);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_indexes($from)
	{
		$this->from($from);
		$sql = "SHOW INDEXES%s;";
		$sql = sprintf($sql,$from);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_create_database($database)
	{
		$this->obj_name($database);
		$sql = "SHOW CREATE DATABASE %s;";
		$sql = sprintf($sql,$database);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_create_table($table)
	{
		$this->obj_name($table);
		$sql = "SHOW CREATE TABLE %s;";
		$sql = sprintf($sql,$table);
		$return = $this->execute($sql);
		return $return;
	}
	public function show_create_view($view)
	{
		$this->obj_name($view);
		$sql = "SHOW CREATE VIEW %s;";
		$sql = sprintf($sql,$view);
		$return = $this->execute($sql);
		return $return;
	}
	public function truncate($table)
	{
		$this->obj_name($table);
		$sql = "TRUNCATE TABLE %s";
		$sql = sprintf($sql,$table);
		$return = $this->execute($sql);
		return $return;
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