<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Helper for easier ajax replies
 */
abstract class ajax {
	
	public static function error($msg, $more = array())
	{
		$base = array('type' => 'error', 'message' => $msg, 'success' => false);
		$reply = array_merge($base, $more);
		die(json_encode($reply));
	}
	
	public static function success($msg, $more = array())
	{
		$base = array('type' => 'success', 'message' => $msg, 'success' => true);
		$reply = array_merge($base, $more);
		die(json_encode($reply));
	}
	
	public static function info($msg, $more = array())
	{
		$base = array('type' => 'info', 'message' => $msg, 'success' => true);
		$reply = array_merge($base, $more);
		die(json_encode($reply));
	}
	
}
