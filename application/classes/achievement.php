<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class achievement {
	
	const FIRST_POST = 'newbie';
	const HAT_TRICK = 'hattrick';
	const WEEK_STREAK = '7daystreak';
	const MONTH_STREAK = 'hotstreak';
	const WRITING_STREAK = 'streak';
	
	public static function add(Model_User $user, Model_Achievement $achievement)
	{
		$userachievement = ORM::factory('Userachievement');
		$userachievement->user_id = $user->id;
		$userachievement->achievement_id = $achievement->id;
		$userachievement->created = time();
		$userachievement->save();
		self::announce($achievement->triggertext);
	}
	
	public static function check_all(Model_User $user)
	{
		self::check_first_post($user);
		self::check_hattrick($user);
		self::check_7daystreak($user);
		self::check_30daystreak($user);
	}
	
	public static function check_first_post(Model_User $user)
	{
		$achievement = ORM::factory('Achievement')
			->where('user_id', '=', $user->id)
			->where('type', '=', self::FIRST_POST)
			->find();
		if(!$achievement->loaded())
		{
			$pages = $user->pages
				->where('type','=','page')
				->where('wordcount','>=',750)
				->count_all();
			if($pages == 1)
			{
				
				self::add($user, $achievement);
			}
		}
	}
	
	public static function check_hattrick(Model_User $user)
	{
		$achievement = ORM::factory('Achievement')
			->where('user_id', '=', $user->id)
			->where('type', '=', self::HAT_TRICK)
			->find();
		if(!$achievement->loaded())
		{
			if($user->current_streak == 3)
			{
				$achievement = ORM::factory('Achievement')
					->where('type', '=', self::HAT_TRICK)
					->find();
				self::add($user, $achievement);
			}
		}
	}
	
	public static function check_7daystreak(Model_User $user)
	{
		$achievement = ORM::factory('Achievement')
			->where('user_id', '=', $user->id)
			->where('type', '=', self::WEEK_STREAK)
			->find();
		if(!$achievement->loaded())
		{
			if($user->current_streak == 7)
			{
				$achievement = ORM::factory('Achievement')
					->where('type', '=', self::WEEK_STREAK)
					->find();
				self::add($user, $achievement);
			}
		}
	}
	
	public static function check_30daystreak(Model_User $user)
	{
		$achievement = ORM::factory('Achievement')
			->where('user_id', '=', $user->id)
			->where('type', '=', self::MONTH_STREAK)
			->find();
		if(!$achievement->loaded())
		{
			if($user->current_streak == 30)
			{
				$achievement = ORM::factory('Achievement')
					->where('type', '=', self::MONTH_STREAK)
					->find();
				self::add($user, $achievement);
			}
		}
	}
	
	public static function announce($message)
	{
		$session = Session::instance();
		$notes = $session->get('achievement_notes');
		if(!$notes)
			$notes = array();
		$content = Array('type' => 'achievement', 'note' => $message);
		$notes[] = $content;
		$session->set('achievement_notes', $notes);
	}
	
	public static function get_announcements()
	{
		$session = Session::instance();
		$messages = $session -> get('achievement_notes');
		$session -> delete('achievement_notes');
		if(isset($messages) && !empty($messages))
			return $messages;
		return false;
	}
	
}
