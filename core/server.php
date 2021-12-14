<?php
class Server {

	// Search route
	public function search_route($path) {
		$path = explode("?", $path)[0];
		if($value = Router::search($path, $_SERVER["REQUEST_METHOD"])) {
			// If passed function
			if (is_callable($value)) $value();
			else $this->route_processing(explode("@", $value));
			return true;
		} else return false;
	}

	// Route processing
	public function route_processing($params) {
		// Checking for file existence
		if(!file_exists("controllers/". $params[0] .".php"))
			return print("File ". $params[0] .".php doesn't exists");

		// Class connection
		include("controllers/". $params[0] .".php");
		// Check the existence of a class
		if(!class_exists($params[0]))
			return print("Class ". $params[0] ." doesn't exists");

		// Instantiating a class
		$controller = new $params[0]();

		// Checking for the existence of a method inside a class
		if(!method_exists($controller, $params[1]))
			return print("Method ". $params[1] ." in class ". $params[0] ." doesn't exists");

		$method = $params[1];

		// Method call
		$controller->$method();
	}

}
?>