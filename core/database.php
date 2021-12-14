<?php
// Database
class Database {
	// Connect
	private $connect;

	// Query data
	private $access = false;
	private $result = "";
	private $result_state = false;
	private $table = "";
	private $table_state = false;
	private $where = "";
	private $where_state = false;
	private $select = "*";
	private $select_state = false;
	private $orderby = "";
	private $orderby_state = false;

	// Connection to base
	function __construct($dbhost, $dbuser, $dbpass, $dbname) {
		if($dbhost == "" || $dbuser == "" || $dbname == "") return;

		$this->connect = null;
		$this->connect = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		$this->connect->set_charset("utf8");
		if($this->connect->connect_errno)
			die("Connection error: ". $this->connect->connect_error);

		$this->access = true;
	}

	// Transaction
	public function transaction($sql_array) {
		if(!$this->access) return $this->connect_error();
		try {
			$this->connect->beginTransaction();
			foreach ($sql_array as $key => $sql)
				$this->connect->query($sql);
			$this->connect->commit();
		} catch (Exception $e) {
			$this->connect->rollback();
			throw $e;
		}
	}

	// Executing sql query
	public function query($sql) {
		if(!$this->access) return $this->connect_error();
		$result = $this->connect->query($sql);
		return $result;
	}

	// Executing a request with further actions
	public function result($sql) {
		if(!$this->access) return $this->connect_error();
		$this->result = $this->connect->query($sql);
		$this->result_state = true;
		return $this;
	}

	// Fluid interface
	// Table
	public function table($table) {
		if(!$this->access) return $this->connect_error();
		$this->table = "`$table`";
		$this->table_state = true;
		$this->where_state = false;
		$this->select_state = false;
		return $this;
	}

	// Selecting a table by attribute
	public function where($field, $condition, $value="") {
		if(!$this->access) return $this->connect_error();
		$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
		$this->where = sprintf("WHERE `%s` %s", $field, $where);
		$this->where_state = true;
		return $this;
	}

	// Additional condition
	public function andWhere($field, $condition, $value="") {
		if(!$this->access) return $this->connect_error();
		if ($this->where_state) {
			$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
			$this->where .= sprintf(" AND `%s` %s", $field, $where);
		} return $this;
	}

	// Additional condition
	public function orWhere($field, $condition, $value) {
		if(!$this->access) return $this->connect_error();
		if ($this->where_state) {
			$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
			$this->where .= sprintf(" OR `%s` %s", $field, $where);
		} return $this;
	}

	// Selecting the fields you want
	public function select($fields) {
		if(!$this->access) return $this->connect_error();
		$string = ""; $counter = 0;
		foreach($fields as $val) {
			if($counter == count($fields) - 1)
				$string .= sprintf("`%s`", trim($val));
			else $string .= sprintf("`%s`, ", trim($val));
			$counter++;
		}
		$this->select = $string;
		$this->select_state = true;
		return $this;
	}

	// Order by
	public function orderBy($value, $type) {
		if(!$this->access) return $this->connect_error();
		$this->orderby = sprintf("ORDER BY `%s` %s", $value, $type);
		$this->orderby_state = true;
		return $this;
	}

	// Get data
	public function get() {
		if(!$this->access) return $this->connect_error();
		if (!$this->result_state) {
			$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
			$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
			$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
			$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
			$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
			$this->result = $this->query($query);
		}
		$array = [];
		while($row = $this->result->fetch_assoc())
			array_push($array, $row);
		return $array;
	}

	// Get first data
	public function first() {
		if(!$this->access) return $this->connect_error();
		if (!$this->result_state) {
			$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
			$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
			$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
			$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
			$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
			$this->result = $this->query($query);
		}
		return $this->result->fetch_assoc();
	}

	// Insert data
	public function insert($array) {
		if(!$this->access) return $this->connect_error();
		$keys = "";
		$values = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1) {
				$keys .= sprintf("`%s`", $key);
				$values .= sprintf("'%s'", $val);
			} else {
				$keys .= sprintf("`%s`, ", $key);
				$values .= sprintf("'%s', ", $val);
			}
			$counter++;
		}
		$query = sprintf("INSERT INTO %s(%s) VALUES (%s)", $this->table, $keys, $values);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Adding data with return ID
	public function insert_id($array) {
		if(!$this->access) return $this->connect_error();
		if ($this->insert($array)) return $this->connect->insert_id;
		else return false;
	}

	// Update data
	public function update($array) {
		if(!$this->access) return $this->connect_error();
		$string = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1)
				$string .= sprintf("`%s`='%s'", $key, $val);
			else $string .= sprintf("`%s`='%s', ", $key, $val);
			$counter++;
		}
		$query = sprintf("UPDATE %s SET %s %s", $this->table, $string, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Delete data
	public function delete() {
		if(!$this->access) return $this->connect_error();
		$query = sprintf("DELETE FROM %s %s", $this->table, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Error output
	public function error() {
		if(!$this->access) return $this->connect_error();
		return $this->connect->error;
	}

	// Connect error
	public function connect_error() {
		if(!$this->access) {
			die("Connection error: There is no data to connect to the database");
		}
	}
}
?>