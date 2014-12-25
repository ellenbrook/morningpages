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
				$last = $user
					->pages
					->where('type','=','page')
					->where('day','=', site::day_slug(strtotime('-1 day',$user->timestamp())))
					->find();
				if($last->loaded())
				{
					$day = 60 * 60 * 24;
					if(($last->created + $day) > $user->timestamp())
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
				else
				{
					$user->current_streak = 0;
					$user->validation_required(false)->save();
				}
			}
		}
	}
	
}
