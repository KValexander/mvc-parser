<?php
class Router {
	// Array of routes
	private static $routes = array();

	// Add GET route
	public static function get($path, $value) {
		$path = slash_check($path);
		self::$routes["GET"][$path] = $value;
	}

	// Add POST route
	public static function post($path, $values) {
		$path = slash_check($path);
		self::$routes["POST"][$path] = $values;
	}

	// Search route
	public static function search($path, $type) {
		if(count(self::$routes) == 0) return false;

		// Checking for route availability
		if($result = self::route_processing($path, $type)) {
			return $result;
		} else return false;
	}

	// Route processing
	private static function route_processing($path, $type) {
		// Simple coincidence
		if (array_key_exists($path, self::$routes[$type]))
			return self::$routes[$type][$path];

		// Checking for the existence of variables in a route
		// Part 1
		$val_path = explode("/", $path);
		foreach(self::$routes[$type] as $key => $val) {
			$count_var = preg_match_all("#{.*?}#", $key);
			$val_key = explode("/", $key);
			if ($count_var > 0 && count($val_path) == count($val_key)) {

				// Determining routes matching
				$pattern = "#". preg_replace("/\{.*?\}/", "(.*?)", $key) ."#";
				if (preg_match($pattern, $path)) {

					// Retrieving values and keys of route variables
					for($i = 0; $i < count($val_path); $i++)
						if(preg_match("/\{.*?\}/", $val_key[$i]))
							Request::add_route(preg_replace("#{|}#", "", $val_key[$i]), $val_path[$i]);

					// Returning result
					return $val;
				}
			}
		}
		
		return false;
	}
}
?>