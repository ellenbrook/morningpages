<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Project {
	
	public $template = 'templates/frontpage';
	
	public function action_index()
	{
		seo::instance()->title("Morning Pages");
		seo::instance()->description("Morning Pages is a website in which users write three pages of stream of consciousness thought and earn rewards, gain self-enlightenment, and most importantly, have fun! Begin writing with no registration!");
	}

}
