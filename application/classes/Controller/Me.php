<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Me extends Controller_Project {
	
	public function action_index()
	{
		$this->require_login();
	}
	
	public function action_options()
	{
		
	}
	
}
