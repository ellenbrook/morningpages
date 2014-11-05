<?php defined('SYSPATH') or die('No direct script access.'); 

class seo {
	
	private static $instance = false;
	
	private static $title = '';
	private static $description = 'Morning Pages is a website in which users write three pages of stream of consciousness thought and earn rewards, gain self-enlightenment, and most importantly, have fun! Begin writing with no registration!';
	
	public static function instance()
	{
		if(!self::$instance)
		{
			self::$instance = new seo();
		}
		return self::$instance;
	}
	
	public function title($title = false)
	{
		if(!$title)
		{
			return self::$title;
		}
		self::$title = $title;
	}
	
	public function description($description = false)
	{
		if(!$description)
		{
			return self::$description;
		}
		self::$description = $description;
	}
	
}
