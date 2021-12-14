<?php
// Calling a view
function view($view, $args=[]) {

	// Checking for file existence
	if(!file_exists("views/".$view.".php"))
		return print("File ". $view .".php doesn't exists");

	// Converting an array to variables
	foreach($args as $key => $val)
		${$key} = $val;

	// Connecting a view
	include "views/".$view.".php";
}
