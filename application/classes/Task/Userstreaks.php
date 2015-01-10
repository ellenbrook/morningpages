<?php defined('SYSPATH') or die('No direct script access.');

class Task_Userstreaks extends Minion_Task {
	
	protected $_options = array();
	
	protected function _execute(array $params)
	{
		$users = ORM::factory('User')
			->where('current_streak', '>', 0)
			->find_all();
		if((bool)$users->count())
		{
			foreach($users as $user)
			{
				$last = $user->pages->find();
				$nextday = site::day_slug(strtotime('+1 day', strtotime($last->day)));
				$yesterday = site::day_slug(strtotime('-1 day', $user->timestamp()));
				$today = site::day_slug($user->timestamp());
				$tomorrow = site::day_slug(strtotime('+1 day', $user->timestamp()));
				
				if($last->day == $yesterday && $nextday != $tomorrow)
				{
					$fail = true;
				}
				else if($last->day != $today && $nextday != $tomorrow)
				{
					$fail = true;
				}
				if($fail)
				{
					$user->current_streak = 0;
					$user->validation_required(false)->save();
					
					if($user->doing_challenge())
					{
						$user->add_event('Failed the 30 day challenge!');
						$user->challenge->delete();
					}
				}
			}
		}
	}
	
}
