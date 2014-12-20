<?php defined('SYSPATH') or die('No direct script access.');

abstract class localization {
	
	public static function get($path)
	{
		return Kohana::$config->load('localization.'.$path);
	}
	
	public static function ziplength($type = 'min')
	{
		return self::get('zipcodes.'.$type);
	}
	
}
