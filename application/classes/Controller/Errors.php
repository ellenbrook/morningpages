<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Project {
	
	public function action_404()
	{
		seo::instance()->title("Page not found");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
		
		$error = ORM::factory('Error');
		$error -> type = '404';
		$error -> url = URL::site(request::detect_uri() . ((isset($_GET)&&!empty($_GET)) ? '?' . http_build_query($_GET) : ''), request::factory());
		$error -> ip = $_SERVER['REMOTE_ADDR'];
		$error -> client = $_SERVER['HTTP_USER_AGENT'];
		$error -> server = serialize($_SERVER);
		$error -> referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$error -> time = time();
		$error -> save();
		// 404 HTTP response
		$this -> response -> status(404);
		
	}
	
}
