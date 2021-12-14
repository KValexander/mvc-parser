<?php
	// Constants
	define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
	define('APP_DOMEN', $_SERVER['REQUEST_SCHEME']. '://' .$_SERVER['HTTP_HOST'] . '/');

	// Headers
	header("Access-Control-Allow-Origin: *");

	// Include include
	include "core/helpers/include.php";

	// Core and helpers include
	include_files("core/");

	// Models include
	include_files("models/");

	// Include class Controller
	include "controllers/Controller.php";

	// Others include
	include "routes.php";

	// Data for connecting to the base
	define("HOST", 		"");
	define("USERNAME", 	"");
	define("PASSWORD", 	"");
	define("DBNAME", 	"");

	// Server
	$server = new Server();

	// Check and processing route in case of availability
	if(!$server->search_route($_SERVER["REQUEST_URI"]))
		return view("index");
		// or echo "<h1>This path doesn't exist</h1>";
		// or return view("404"); in view

?>