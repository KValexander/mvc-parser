<?php
// Main controller
class MainController extends Controller {
	
	// Main page
	public function main_page() {
		$url = $this->request->input("url");
		$parser = new Parser();
		$html = $parser->url($url)
			->wrap("ul", "class", "novel-list", 0)
			->content("li", "class", "novel-item")
			->link("href")
			->link("data-src")
			->get();
		return response(200, $html);
	}

	// Novels page
	public function novels_page() {
		$page = $this->request->route("page");
		$url = $this->request->input("url");
		$parser = new Parser();
		$html = $parser->url($url)
			->wrap("ul", "class", "novel-list horizontal col2", 0)
			->content("li", "class", "novel-item")
			->link(" href")
			->link("data-src")
			->get();

		return response(200, array_slice($html, 0, 24));
	}

}

?>