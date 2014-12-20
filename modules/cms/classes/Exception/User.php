<?php defined('SYSPATH') or die('No direct script access.');

class Exception_User extends Exception {
	
	public function __construct($message = null, $code = 0)
	{
		if(!$message)
		{
			$message = __('Please make sure you\'re logged in to continue');
		}
		parent::__construct($message, $code);
	}
	
}
