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
					->limit(1)
					->find();
				if($last->loaded())
				{
					$day = 60 * 60 * 24;
					if($last->created < time() - $day)
					{
						$user->current_streak = 0;
						$user->save();
					}
				}
			}
		}
	}
	
}