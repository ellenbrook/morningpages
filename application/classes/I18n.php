<?php defined('SYSPATH') OR die('No direct script access.');

class I18n extends Kohana_I18n {
	
	public static function get($string, $lang = NULL)
	{
		if ( ! $lang)
		{
			// Use the global target language
			$lang = I18n::$lang;
		}

		// Load the translation table for this language
		$table = I18n::load($lang);
		if(!isset($table[$string]))
		{
			$file = Kohana::find_file('i18n', 'nottranslated');
			//$f = fopen($file[0], 'r');
			$content = file_get_contents($file[0]);
			$lines = explode("\n", $content);
			if(!in_array($string, $lines))
			{
				$lines[] = $string;
				$content = implode("\n", $lines);
				$f = fopen($file[0], 'w');
				fwrite($f, $content);
			}
			
		}
		// Return the translated string if it exists
		return isset($table[$string]) ? $table[$string] : $string;
	}
	
}

function __($string, array $values = NULL, $lang = 'en-us')
{
	if ($lang !== I18n::$lang)
	{
		// The message and target languages are different
		// Get the translation for this message
		$string = I18n::get($string);
	}

	return empty($values) ? $string : strtr($string, $values);
}

function _e($string, array $values = NULL, $lang = 'en-us')
{
	echo __($string, $values, $lang);
}
