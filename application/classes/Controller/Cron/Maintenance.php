<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron_Maintenance extends Controller {
	
	public function action_index()
	{
		$users = ORM::factory('User')->find_all();
		foreach($users as $user)
		{
			$options = ORM::factory('User_Option');
			$options->user_id = $user->id;
			$options->save();
	}
	
}
