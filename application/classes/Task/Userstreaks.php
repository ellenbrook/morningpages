<?php defined('SYSPATH') or die('No direct script access.');

class Task_Userstreaks extends Minion_Task {
	
	protected $_options = array();
	
	protected function _execute(array $params)
	{
		$this->check_streaks();
		$this->check_challenges();
	}
	
	private function check_challenges()
	{
		$challenges = DB::query(Database::SELECT, "SELECT user.id, challenge.start FROM `users` AS user JOIN `user_challenges` AS challenge ON user.id = challenge.user_id")
			->execute()
			->as_array();
		if(!empty($challenges))
		{
			foreach($challenges as $challenge)
			{
				$user = ORM::factory('User', arr::get($challenge, 'id'));
				$start = arr::get($challenge, 'start');
				$start = $user->challenge->start;
				if($user->timestamp()-$start > (24*60*60) && $user->current_streak == 0)
				{
					$user->fail_challenge();
				}
			}
		}
	}
	
	private function check_streaks()
	{
		$users = ORM::factory('User')
			->where('current_streak', '>', 0)
			->find_all();
		if((bool)$users->count())
		{
			foreach($users as $user)
			{
				$last = $user->pages->where('type','=','page')->find();
				if($last->loaded())
				{
					$nextday = site::day_slug(strtotime('+1 day', strtotime($last->day)));
					
					$today = site::day_slug($user->timestamp());
					$tomorrow = site::day_slug(strtotime('+1 day', $user->timestamp()));
					
					if($nextday != $today && $nextday != $tomorrow)
					{
						$user->current_streak = 0;
						$user->validation_required(false)->save();
						
						if($user->doing_challenge())
						{
							$user->fail_challenge();
						}
					}
				}
				else
				{
					if($user->doing_challenge())
					{
						$start = $user->challenge->start;
						if($user->timestamp()-$start > (24*60*60))
						{
							$user->fail_challenge();
						}
					}
				}
			}
		}
	}
	
}
