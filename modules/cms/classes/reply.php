<?php defined('SYSPATH') or die('No direct script access.');

abstract class reply {
	
	public static function redirect($url, $msg = '', $more = array())
	{
		$data = array(
			'type' => 'redirect',
			'url' => $url,
			'message' => $msg
		);
		$data = array_merge($data, $more);
		die(json_encode($data));
	}
	
	public static function ok($view, $domain, $more = array())
	{
		$data = array(
			'type' => 'success',
			'code' => 200,
			'view' => $view->render(),
			'domain' => $domain
		);
		$data = array_merge($data, $more);
		die(json_encode($data));
	}
	
}
