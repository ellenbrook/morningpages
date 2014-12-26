<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Project {
	
	public function action_404()
	{
		seo::instance()->title("Page not found");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
	}
	
}
