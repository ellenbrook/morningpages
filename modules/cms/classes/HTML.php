<?php defined('SYSPATH') or die('No direct script access.');

abstract class HTML extends Kohana_HTML {
	
	public static function image($file, array $attributes = NULL, $protocol = NULL, $index = FALSE)
	{
		// Add the image link
		$attributes['src'] = $file;
		return '<img'.HTML::attributes($attributes).' />';
	}
	
}
