<?php defined('SYSPATH') or die('No direct script access.');
 
/**
 * Helper for easier ajax replies
*/
abstract class ajax {
	
	public static function redirect($url, $msg = false)
	{
		self::doit(true, 'redirect', $msg, array('url' => $url));
	}
	
	public static function error($msg, $more = array())
	{
		self::doit(false, 'error', $msg, $more);
	}
	
	public static function success($msg = '', $more = array())
	{
		self::doit(true, 'success', $msg, $more);
	}
	
	public static function info($msg, $more = array())
	{
		self::doit(true, 'info', $msg, $more);
	}
	
	private static function doit($success, $type, $msg = '', $more = array())
	{
		$values = array(
			'success' => $success,
			'type' => $type,
			'message' => $msg,
		);
		$reply = array_merge($values, $more);
		die(json_encode($reply));
	}
	
}