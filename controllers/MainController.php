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
			->get_content();
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
			->get_content();

		return response(200, array_slice($html, 0, 24));
	}

	// Novel page
	public function novel_page() {
		$id = $this->request->route("id");
		$url = $this->request->input("url");
		$parser = new Parser();
		$content = $parser->url($url)
			->wrap("header", "class", "novel-header", 0)
			->link("data-src")
			->content("div", "class", "main-head")
			->wrap("nav", "class", "content-nav", 0)
			->content("p", "class", "latest text1row")
			->link("href")
			->get_content();

		$chapters = $parser->url("https://www.lightnovelspot.com/". $content[0][count($content[0]) - 1])
			->wrap("ul", "class", "chapter-list")
			->link("href")
			->content("strong", "class", "chapter-title")
			->get_content();

		return response(200, [
			"content" => $content[0],
			"chapters" => $chapters[0]
		]);
	}

	// Chapter page
	public function chapter_page() {
		$id = $this->request->route("id");
		$url = $this->request->input("url");
		$parser = new Parser();
		$info = $parser->url($url)
			->wrap("section", "class", "page-in content-wrap", 0)
			->content("div", "class", "titles")
			->get_content();
		$content = $parser->content("p")->get_content();

		return response(200, [
			"info" => $info,
			"content" => $content
		]);
	}

}

?>