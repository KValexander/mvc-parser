<?php
	// Api route
	Router::get("/api/main", "MainController@main_page");
	Router::get("/api/novels/{page}", "MainController@novels_page");
	Router::get("/api/novel/{id}", "MainController@novel_page");
	Router::get("/api/novel/{id}/{chapter}", "MainController@chapter_page");