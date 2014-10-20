<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_User extends Controller {
	
	public function action_info()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in');
		}
		
		$user = user::get();
        $today = $user->pages->where('day','=',site::today_slug())->find();
        $wordcount = 0;
        if($today->loaded())
        {
            $wordcount = $today->wordcount;
        }
		ajax::success('ok', array(
			'email' => $user->email,
			'wordcount' => $wordcount,
			'options' => array(
				'reminder' => (bool)$user->option->reminder,
				'reminder_hour' => $user->option->reminder_hour,
				'reminder_minute' => $user->option->reminder_minute,
				'reminder_meridiem' => $user->option->reminder_meridiem,
				'timezone_id' => $user->option->timezone_id,
				'theme_id' => $user->option->theme_id,
				'privacymode' => (bool)$user->option->privacymode,
				'privacymode_minutes' => $user->option->privacymode_minutes,
				'hemingwaymode' => (bool)$user->option->hemingwaymode,
				'public' => (bool)$user->option->public,
			)
		));
	}
	
	public function action_login()
	{
		if(user::logged())
		{
			ajax::error('You are already logged in as '.user::get()->username());
		}
		
		if($_POST)
		{
			$email = arr::get($_POST, 'email','');
			$password = arr::get($_POST, 'password','');
			$remember = false;//(arr::get($_POST, 'remember','')=='yes');
			if(user::login($email, $password, $remember))
			{
				$user = user::get();
				if($user->delete != 0) // Previously set for deletion. Cancel that
				{
					$user->delete = 0;
					$user->save();
				}
				ajax::success('You have been logged in. Welcome back!');
			}
			else
			{
				//notes::error('Wrong username or password. Please try again.');
				ajax::error('Invalid username or password.');
			}
		}
		else
		{
			ajax::error('No data received');
		}
		
	}
	
	public function action_savetheme()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in');
		}
		$user = user::get();
		$option = $user->option;
		$option->theme_id = arr::get($_POST, 'id',0);
		try
		{
			$option->save();
			ajax::success('Saved',array(
				'theme' => $option->theme->name
			));
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('An error occurred and your theme setting couldn\'t be saved.', array(
				'errors' => $e->errors()
			));
		}
	}
	
	public function action_savesetting()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in');
		}
		$user = user::get();
		$option = $user->option;
		$setting = arr::get($_POST, 'setting',false);
		$value = arr::get($_POST, 'value',false);
		if(!$setting || $value === false)
		{
			ajax::error('Something wen\'t wrong and your setting couldn\'t be saved. I received no data!');
		}
		switch($setting)
		{
			case 'reminder':
				$option->reminder = $value;
				break;
			case 'reminder_hour':
				$option->reminder_hour = $value;
				break;
			case 'reminder_minute':
				$option->reminder_minute = $value;
				break;
			case 'reminder_meridiem':
				$option->reminder_meridiem = $value;
				break;
			case 'timezone_id':
				$option->timezone_id = $value;
				break;
			case 'privacymode':
				$option->privacymode = $value;
				break;
			case 'privacymode_minutes':
				$option->privacymode_minutes = $value;
				break;
			case 'hemingwaymode':
				$option->hemingwaymode = $value;
				break;
			case 'public':
				$option->public = $value;
				break;
			default:
				ajax::error('Something wen\'t wrong and your setting couldn\'t be saved. I received no data!');
				break;
		}
		try
		{
			$option->save();
			ajax::success('Saved');
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('An error occurred and your setting couldn\'t be saved.', array(
				'errors' => $e->errors()
			));
		}
	}
	
}
	