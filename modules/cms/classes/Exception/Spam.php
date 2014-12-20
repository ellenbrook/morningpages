<?php defined('SYSPATH') or die('No direct script access.');

class Exception_Spam extends Exception {
	
	public function __construct($message = null, $code = 0)
	{
		if(!$message)
		{
			$message = __('This seems to have been caught by our spamfilter. If this is an error, we apologise and ask that you contact us and we\'ll get this fixed immediately');
		}
		parent::__construct($message, $code);
	}
	
}
