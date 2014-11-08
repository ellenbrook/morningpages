<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron_Maintenance extends Controller {
	
	public function action_index()
	{
		$users = ORM::factory('User')->find_all();
		foreach($users as $user)
		{
			$slug = site::slugify($user->username);
			$orgslug = $slug;
			$existing = ORM::factory('User')->where('slug','=',$slug)->find();
			$i = 2;
			while($existing->loaded())
			{
				$slug = $orgslug.'-'.$i;
				$i++;
			}
			$user->slug = $slug;
			$user->update();
		}
	}
	
}
