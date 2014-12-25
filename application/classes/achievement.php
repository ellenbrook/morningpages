<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class achievement {
	
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
		$userachievements = $user->userachievements->find_all();
		$gotem = array();
		foreach($userachievements as $userachievement)
		{
			$gotem[$userachievement->achievement->type] = $userachievement->achievement;
		}
		
		if(!array_key_exists('newbie', $gotem))
		{
			if(self::check_newbie($user))
			{
				self::add($user, $gotem['newbie']);
			}
		}
		if(!array_key_exists('hattrick', $gotem))
		{
			if(self::check_hattrick($user))
			{
				self::add($user, $gotem['hattrick']);
			}
		}
		if(!array_key_exists('7daystreak', $gotem))
		{
			if(self::check_7daystreak($user))
			{
				self::add($user, $gotem['7daystreak']);
			}
		}
		if(!array_key_exists('hotstreak', $gotem))
		{
			if(self::check_hotstreak($user))
			{
				self::add($user, $gotem['hotstreak']);
			}
		}
	}
	
	public static function check_newbie(Model_User $user)
	{
		$pages = $user->pages
			->where('type','=','page')
			->where('wordcount','>=',750)
			->count_all();
		if($pages == 1)
		{
			return true;
		}
		return false;
	}
	
	public static function check_hattrick(Model_User $user)
	{
		if($user->current_streak == 3)
		{
			return true;
		}
		return false;
	}
	
	public static function check_7daystreak(Model_User $user)
	{
		if($user->current_streak == 7)
		{
			return true;
		}
		return false;
	}
	
	public static function check_hotstreak(Model_User $user)
	{
		if($user->current_streak == 30)
		{
			return true;
		}
		return false;
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
