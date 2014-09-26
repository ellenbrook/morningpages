<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Me extends Controller_Project {
	
	public function action_index()
	{
		notes::success('Success message');
		notes::error('Error message');
		notes::info('Info message');
		$this->require_login();
	}

}
