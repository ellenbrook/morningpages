<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Me extends Controller_Project {
	
	public function action_index()
	{
		$this->require_login();
		$this->bind('user',user::get());
	}
	
	public function action_profile()
	{
		$user = $this->request->param('user');
		$this->template->view = View::factory('Me/index');
		$this->bind('user',$user);
	}
	
	public function action_notpublic()
	{
		
	}
	
}
