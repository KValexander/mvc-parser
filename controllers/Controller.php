<?php
// Parent class of controllers
class Controller {
	protected $db;
	protected $request;
	protected $validator;

	// Constructor
	function __construct() {
		$this->db = new Database(HOST, USERNAME, PASSWORD, DBNAME);
		$this->request = new Request();
		$this->validator = new Validator($db);
	}

	// Destructor
	function __destruct() {
		unset($this->db);
		unset($this->request);
		unset($this->validator);
	}

}

?>