<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class achievement {
	
	public static function add(Model_User $user, Model_Achievement $achievement)
	{
		$userachievement = ORM::factory('Userachievement');
		$userachievement->user_id = $user->id;
		$userachievement->achievement_id = $achievement->id;
		$userachievement->created = time();
		$userachievement->save();
		notes::achievement($achievement->triggertext);
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
				user::award_points(10, 'Wrote first post over 750 words and earned 10 extra points!', $user);
			}
		}
		if(!array_key_exists('hattrick', $gotem))
		{
			if(self::check_hattrick($user))
			{
				self::add($user, $gotem['hattrick']);
				user::award_points(20, 'Wrote 3 days in a row and earned 20 extra points!', $user);
			}
		}
		if(!array_key_exists('7daystreak', $gotem))
		{
			if(self::check_7daystreak($user))
			{
				self::add($user, $gotem['7daystreak']);
				user::award_points(30, 'Wrote 7 days in a row and earned 30 extra points!', $user);
			}
		}
		if(!array_key_exists('hotstreak', $gotem))
		{
			if(self::check_hotstreak($user))
			{
				self::add($user, $gotem['hotstreak']);
				user::award_points(40, 'Wrote 30 days in a row and earned 50 extra points!', $user);
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
	
}
