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
		self::check($user, self::FIRST_POST);
		self::check($user, self::HAT_TRICK);
		self::check($user, self::WEEK_STREAK);
		self::check($user, self::MONTH_STREAK);
	}
	
	public static function check(Model_User $user, $type, $value = false)
	{
		$achievement = ORM::factory('Achievement')
			->where('type','=',$type)
			->find();
		if(!$achievement->loaded())
		{
			return false;
		}
		switch($type)
		{
			case self::FIRST_POST:
				$pages = $user->pages
					->where('type','=','page')
					->where('wordcount','>=',750)
					->count_all();
				if($pages == 1)
				{
					$achievement = ORM::factory('Achievement')
						->where('type', '=', self::FIRST_POST)
						->find();
					self::add($user, $achievement);
				}
				break;
			case self::HAT_TRICK:
				$existing = $user->userachievements->where('achievement_id','=',$achievement)->find();
				if($existing->loaded())
				{
					return false;
				}
				if($user->current_streak == 3)
				{
					$achievement = ORM::factory('Achievement')
						->where('type', '=', self::HAT_TRICK)
						->find();
					self::add($user, $achievement);
				}
				break;
			case self::WEEK_STREAK:
				$existing = $user->userachievements->where('achievement_id','=',$achievement)->find();
				if($existing->loaded())
				{
					return false;
				}
				if($user->current_streak == 7)
				{
					$achievement = ORM::factory('Achievement')
						->where('type', '=', self::WEEK_STREAK)
						->find();
					self::add($user, $achievement);
				}
				break;
			case self::MONTH_STREAK:
				$existing = $user->userachievements->where('achievement_id','=',$achievement)->find();
				if($existing->loaded())
				{
					return false;
				}
				if($user->current_streak == 7)
				{
					$achievement = ORM::factory('Achievement')
						->where('type', '=', self::MONTH_STREAK)
						->find();
					self::add($user, $achievement);
				}
				break;
			case self::WRITING_STREAK:
				if($value === false)
				{
					return false;
				}
				if($user->current_streak >= $value)
				{
					return true;
				}
				return false;
			default:
				return;
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
