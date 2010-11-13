<?php
class w3v_Table extends b3v_Table
{
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
		global $wpdb;
		$return = $wpdb->get_results ( $sql , ARRAY_A );
		return $return;
	}

	public function explode_fields ( $fields )
	{
		if (null === $fields)
		{
			$fields = $this->key_fields ();
			if (! is_array ( $fields ))
			{return "\n\t$fields\n";}
		}
		$table = '`' . $this->name () . '`';
		$fields = implode ( "` ,\n\t$table.`" , $fields );
		$fields = "\t$table.`$fields`\n";
		return $fields;
	}

	public function get_results ( $sql )
	{
		return $this->wpdb ()->get_results ( $sql , ARRAY_A );
	}

	protected function wpdb ()
	{
		global $wpdb;
		return $wpdb;
	}

	public function key_fields ()
	{
		return '*';
	}

	public function explode_where ( $where )
	{
		if (null === $where)
		{
			$where = "";
		}
		$where = trim ( $where );
		if ($where != "")
		{
			$where = "\nWHERE\t" . $where;
		}
		return $where;
	}

	public function explode_limit ( $limit )
	{
		if (null === $limit)
		{
			$limit = "";
		}
		$limit = trim ( $limit );
		if ($limit != "")
		{
			$limit = "\nLIMIT\t" . $limit;
		}
		return $limit;
	}

	public function select ( $fields = null , $where = null , $limit = null , $from = null , $distinct = false )
	{
		$fields = $this->explode_fields ( $fields );
		$where = $this->explode_where ( $where );
		$limit = $this->explode_limit ( $limit );
		$from = $this->explode_from ( $from );
		if ($distinct)
		{
			$distinct = ' DISTINCT ';
		}
		else
		{
			$distinct = '';
		}
		$sql = "SELECT%s%sFROM\t%s%s%s;";
		$sql = sprintf ( $sql , $distinct , $fields , $from , $where , $limit );
		$results = $this->get_results ( $sql );
		return $results;
	}

	public function explode_from ( $from = null )
	{
		if (null === $from)
		{return '`' . $this->name () . '`';}
		return $from;
	}

	protected function prefix ()
	{
		return $this->wpdb ()->get_blog_prefix ( $this->blog_id () );
	}
	private $_blog_id = null;

	protected function set_blog_id ( $blog_id )
	{
		$this->_blog_id = $blog_id;
	}

	protected function blog_id ()
	{
		return $this->_blog_id;
	}

	public function get ()
	{
		return $this->get_by_clause ();
	}

	public function get_by_clause ( $where = "" , $limit = "" )
	{
		if ($where != "")
		{
			$where = " WHERE " . $where;
		}
		if ($limit != "")
		{
			$limit = " LIMIT " . $limit;
		}
		$sql = sprintf ( 'SELECT * FROM %s%s%s' , $this->name () , $where , $limit );
		$results = $this->wpdb ()->get_results ( $sql , ARRAY_A );
		return $results;
	}

	public function get_row_by_clause ( $where = "" )
	{
		$results = $this->get_by_clause ( $where , 1 );
		foreach ( $results as $result )
		{
			return $result;
		}
		return false;
	}

	public function __construct ( $blog_id = null , $name = null )
	{
		parent::__construct();
		$this->set_blog_id ( $blog_id );
		$this->set_name ( $name );
	}

	public function count ()
	{
		return $this->count_by_clause ();
	}

	public function count_by_clause ( $where = "" )
	{
		if ($where != "")
		{
			$where = " WHERE " . $where;
		}
		$sql = sprintf ( "SELECT count(*) 'count' FROM %s%s" , $this->name () , $where );
		$results = $this->wpdb ()->get_results ( $sql , ARRAY_A );
		return $results [0] ['count'];
	}
}