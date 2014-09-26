<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class achievement {
	
	const FIRST_POST = 'newbie';
	const WRITING_STREAK = 'streak';
	
	public static function add(Model_User $user, Model_Achievement $achievement)
	{
		switch($type)
		{
			case self::FIRST_POST:
				$userachievement = ORM::factory('Userachievement');
				$userachievement->user_id = $user->id;
				$userachievement->achievement_id = $achievement->id;
				$userachievement->created = time();
				$userachievement->save();
			default:
				return;
		}
	}
	
	public static function check(Model_User $user, $type, $value = false)
	{
		switch($type)
		{
			case self::FIRST_POST:
				$pages = $user->pages->where('wordcount','>=',750)->count_all();
				if($pages == 1)
				{
					$achievement = ORM::factory('Achievement')
						->where('type', '='. self::FIRST_POST)
						->find();
					self::add($user, $achievement);
					self::announce($achievement->triggertext);
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
		$content = Array('type' => $type, 'note' => $message);
		$notes[] = $content;
		$session->set('achievement_notes', $popnotes);
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
