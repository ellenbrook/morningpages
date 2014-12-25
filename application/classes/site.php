<?php defined('SYSPATH') or die('No direct script access.'); 

/**
 * Various site functions.
 */

abstract class site {
	
	public static function notes()
	{
		$notes = notes::fetch();
		if(!is_array($notes)) $notes = array();
		return json_encode($notes);
	}
	
	public static function locale()
	{
		return Kohana::$config->load('site')->get('locale');
	}
	
	public static function count_words($string)
	{
		return count(preg_split('/[\s,.]+/u', $string));
	}
	
	public static function jslocale()
	{
		$locale = self::locale();
		$locale = strtolower($locale);
		return str_replace('_','-', $locale);
	}
	
	public static function count_total_words()
	{
		$numwords = DB::select(DB::expr("SUM(`wordcount`) AS count"))
			->from('pages')
			->where('day','=', site::day_slug())
			->execute()
			->as_array();
		$numwords = arr::get($numwords[0],'count',0);
		return $numwords;
	}
	
	public static function day_slug($timestamp = false)
	{
		if($timestamp)
		{
			return date(self::dateformat(), $timestamp);
		}
		return date(self::dateformat());
	}
	
	public static function dateformat()
	{
		return 'Y-m-d';
	}
	
	public static function name()
	{
		return 'Morning pages';
	}
	
	public static function option($type)
	{
		switch($type)
		{
			case 'sitename':
				return 'Morning pages';
				break;
			case 'emailfrom':
				return 'do-not-reply@morningpages.net';
				return 'ericellenbrook@gmail.com';
				break;
			default:
				return false;
				break;
		}
	}
	
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
		$slug = str_replace(array(' og ',' at ',' den ',' det ',' der ',' som '), ' ', $slug);
		$slug = str_replace(array('æ', 'ø', 'å'), array('ae', 'o', 'aa'), $slug);
		$slug = str_replace(array('/', '\\', '_', '&'), '-', $slug);
		return URL::title($slug, '-', true);
	}
	
	public static function markdown($text)
	{
		if(!class_exists('Markdown'))
		{
			require Kohana::find_file('vendor', 'markdown/Markdown');
		}
		return Markdown::defaultTransform($text);
	}
	
	/**
	 * Joins, caches and serves CSS files.
	 * When there are changes a new file will be created otherwise it'll serve the cached version.
	 * Automatically cleans up the cache folder as well.
	 * @param Array css files.
	 * @param bool Cache the files?
	 * @return String Link tag for the cached file
	 */
	public static function css(Array $files, $cache = true)
	{
		$cssname = md5(implode('',$files)) . '.css';
		$cssname = '';
		foreach($files as $file)
		{
			if((strpos($file, 'http://') === false ) && strpos($file, '.css') && file_exists($file))
			{
				$cssname .= $file . filemtime($file);
			}
			else
			{
				$cssname .= $file;
			}
		}
		$cssname = md5($cssname) . '.css';
		if(!file_exists('media/cache/' . $cssname))
		{
			$content = '';
			foreach($files as $file)
			{
				if( ( strpos($file, 'http') !== false ) || file_exists($file) )
				{
					if(strpos($file, 'http') !== false)
					{
						$content .= file_get_contents($file);
					}
					else
					{
						$content .= file_get_contents($file);
					}
				}
			}
			file_put_contents('media/cache/' . $cssname, $content);
			unset($content);
			$oldfiles = glob('media/cache/*.css'); // Cleaning up the cache folder
			if($oldfiles > 1)
			{
				foreach($oldfiles as $oldfile)
				{
					if(time() - filemtime($oldfile) > Date::MINUTE)
					{
						unlink($oldfile);
					}
				}
			}
		}
		return HTML::style('media/cache/' . $cssname);
	}
	
	/**
	 * Joins, caches and serves JS files.
	 * When there are changes a new file will be created otherwise it'll serve the cached version.
	 * Automatically cleans up the cache folder as well.
	 * @param Array js files.
	 * @return String script tag to cached file.
	 * @param String Script tag for the cached file
	 */
	public static function js(Array $files)
	{
		$start = time();
		$jsname = '';
		foreach($files as $file)
		{
			if((strpos($file, 'http://') === false ) && strpos($file, '.js') && file_exists($file))
			{
				$jsname .= $file . filemtime($file);
			}
			else
			{
				$jsname .= $file;
			}
		}
		$jsname = md5($jsname) . '.js';
		if(!file_exists('media/cache/' . $jsname))
		{
			$content = '';
			foreach($files as $file)
			{
				if((strpos($file, '.js')===false) || (strpos($file, 'http://') !== false ) || file_exists($file)) // Check if local file exists (don't check external files - we trust google [and don't want to bother with timeouts etc when checking])
				{
					$content .= file_get_contents($file);
				}
			}
			file_put_contents('media/cache/' . $jsname, $content);
			unset($content);
			$oldfiles = glob('media/cache/*.js'); // Cleaning up the cache folder
			if($oldfiles > 1)
			{
				foreach($oldfiles as $oldfile)
				{
					if(time() - filemtime($oldfile) > Date::MINUTE)
					{
						unlink($oldfile);
					}
				}
			}
		}
		return HTML::script('media/cache/' . $jsname);
	}
	
	/**
	 * Compiles .less files, joins and serves CSS.
	 * @param Array css files.
	 * @param bool Cache the files?
	 */
	public static function _css(Array $files, $cache = true)
	{
		// If this is the exception controller, the media/cache folder can't be found, so skip caching
		if(is_dir('media/cache'))
		{
			$cssname = md5(implode('',$files)) . '.css';
			if(($cache && !file_exists(URL::site('media/cache/' . $cssname, 'http'))) || !$cache)
			{
				$content = '';
				foreach($files as $file)
				{
					if( ( strpos($file, 'http') !== false ) || file_exists($file) )
					{
						$content .= @file_get_contents($file);
					}
				}
				@file_put_contents('media/cache/'.$cssname, $content);
				unset($content);
			}
		return HTML::style('media/cache/' . $cssname);
		}
		else
		{
			$return = '';
			foreach($files as $file)
			{
				$return .= HTML::style($file);
			}
			return $return;
		}
	}
	
	/**
	 * Joins and serves JS files.
	 * @param Array js files.
	 * @param bool Cache the files?
	 */
	public static function _js(Array $files, $cache = true)
	{
		// If this is the exception controller, the media/cache folder can't be found, so skip caching
		if(is_dir('media/cache'))
		{
			$jsname = md5(implode('',$files)) . '.js';
			if(($cache && !file_exists(url::site('media/cache/' . $jsname, 'http'))) || !$cache)
			{
				$content = '';
				foreach($files as $file)
				{
					if(((strpos($file, 'http://') !== false )||(strpos($file, 'https://') !== false )) || file_exists($file)) // Check if local file exists (don't check external files - we trust google [and don't want to bother with timeouts etc when checking])
					{
						$content .= @file_get_contents($file);
					}
				}
				@file_put_contents('media/cache/' . $jsname, $content);
				unset($content);
			}
			return HTML::script('media/cache/' . $jsname);
		}
		else
		{
			$return = '';
			foreach($files as $file)
			{
				$return .= HTML::script($file);
			}
			return $return;
		}
	}
	
	public static function jsreply($type, $msg, $more = array())
	{
		$base = array('response' => $type, 'message' => $msg);
		$reply = array_merge($base, $more);
		return json_encode($reply);
	}
	
}
