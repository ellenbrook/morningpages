<?php defined('SYSPATH') or die('No direct script access.'); 

/**
 * Various site functions.
 * @package Byggetilbud/Helpers
 */

abstract class site {
	
	/**
	 * Returns the current URL
	 * @return String url
	 */
	public static function current_url()
	{
		$url = Request::factory()->uri();
		if($_GET)
		{
			$url .= '/?' . $_SERVER['QUERY_STRING'];
		}
		$url = URL::site( $url, TRUE);
		return $url;
	}
	
	public static function set_last_url($url)
	{
		session::instance()->set('last_url', $url);
	}
	
	public static function get_last_url($delete = true)
	{
		$url = session::instance()->get('last_url', false);
		if($delete)
		{
			session::instance()->delete('last_url');
		}
		return $url;
	}
	
	/**
	 * Returns the site title with the config title appended.
	 * @return String Page title
	 */
	public static function title($page_title = false)
	{
		$title = '';
		if($page_title)
		{
			$title = $page_title . ' | ';
		}
		return $title . Kohana::$config->load('site')->get('name');
	}
	
	/**
	 * request::factory()->redirect() alias.
	 */
	public static function redirect($to = '')
	{
		HTTP::redirect($to);
		die();
	}
	
	/**
	 * Slugifies titles
	 * @param String String to slugify
	 * @return String Slugified string
	 */
	public static function slugify($slug)
	{
		$slug = mb_strtolower($slug);
		$slug = trim($slug);
		$slug = str_replace(array(' og ',' at ',' den ',' det ',' der ',' som '), ' ', $slug);
		$slug = str_replace(array('æ', 'ø', 'å'), array('ae', 'o', 'aa'), $slug);
		$slug = str_replace(array('/', '\\', '_', '&'), '-', $slug);
		return URL::title($slug, '-', true);
	}
	
	/**
	 * Compiles .less files, joins and serves CSS.
	 * @param Array css files.
	 * @param bool Cache the files?
	 */
	public static function css(Array $files, $cache = true)
	{
		require_once(kohana::find_file('vendor', 'lessphp/lessc.inc', 'php'));
		$cssname = md5(implode('',$files)) . '.css';
		if(($cache && !file_exists(url::site('media/cache/' . $cssname, 'http'))) || !$cache)
		{
			$content = '';
			foreach($files as $file)
			{
				if( ( strpos($file, 'http') !== false ) || file_exists($file) )
				{
					if(strpos($file, '.less') !== false)
					{
						// It's a LESS file that we need to compile
						$less = @file_get_contents($file);
						$compiler = new lessc();
						$content .= $compiler->parse($less);
					}
					else
					{
						$content .= @file_get_contents($file);
					}
				}
			}
			file_put_contents('media/cache/' . $cssname, $content);
			unset($content);
		}
		return HTML::style('media/cache/' . $cssname);
	}
	
	public static function name()
	{
		return 'Offers template';
	}
	
	public static function option($type)
	{
		switch($type)
		{
			case 'sitename':
				return 'Offers template';
				break;
			case 'emailfrom':
				return 'daniel@danwebs.dk';
				break;
			default:
				return false;
				break;
		}
	}
	
	public static function locale()
	{
		return Kohana::$config->load('site')->get('locale');
	}
	
	public static function jslocale()
	{
		$locale = self::locale();
		$locale = strtolower($locale);
		return str_replace('_','-', $locale);
	}
	
	/**
	 * Joins and serves JS files.
	 * @param Array js files.
	 * @param bool Cache the files?
	 */
	public static function js(Array $files, $cache = true)
	{
		$start = time();
		$jsname = md5(implode('',$files)) . '.js';
		if(($cache && !file_exists(url::site('media/cache/' . $jsname, 'http'))) || !$cache)
		{
			$content = '';
			foreach($files as $file)
			{
				if((strpos($file, 'http://') !== false ) || file_exists($file)) // Check if local file exists (don't check external files - we trust google [and don't want to bother with timeouts etc when checking])
				{
					$content .= @file_get_contents($file);
				}
			}
			file_put_contents('media/cache/' . $jsname, $content);
			unset($content);
		}
		return HTML::script('media/cache/' . $jsname);
	}
	
	public static function jsreply($type, $msg, $more = array())
	{
		$base = array('response' => $type, 'message' => $msg);
		$reply = array_merge($base, $more);
		return json_encode($reply);
	}
	
}
