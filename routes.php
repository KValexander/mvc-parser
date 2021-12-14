<?php
	// Api route
	Router::get("/api/main", "MainController@main_page");
	Router::get("/api/novels/{page}", "MainController@novels_page");