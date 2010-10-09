<?php
/**
 * routines to standardise and black box access to mysql
 * @package Library
 * @subpackage Mysql
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Mysql extends D5vCode_Base
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
	
}
