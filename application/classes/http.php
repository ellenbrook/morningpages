<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class http extends Kohana_HTTP {
	
	// $type must equal 'GET' or 'POST'
	// http://stackoverflow.com/questions/962915/how-do-i-make-an-asynchronous-get-request-in-php
	// Faux async calls
	public static function curl_request_async($url, $params = array(), $type='POST', $timeout = 30)
	{
		$post_params = array();
		foreach ($params as $key => &$val)
		{
			if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		
		$parts=parse_url($url);
		
		$fp = fsockopen($parts['host'],
		isset($parts['port'])?$parts['port']:80,
		$errno, $errstr, $timeout);
		
		  // Data goes in the path for a GET request
		if('GET' == $type) $parts['path'] .= '?'.$post_string;
		
		$out = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		// Data goes in the request body for a POST request
		if ('POST' == $type && isset($post_string)) $out.= $post_string;
		
		fwrite($fp, $out);
		fclose($fp);
	}
	
}
