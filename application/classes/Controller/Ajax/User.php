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
				'rtl' => (bool)$user->option->rtl,
			)
		));
	}
	
	public function action_fbsignup()
	{
		if($_POST)
		{
			$serviceid = arr::get($_POST, 'serviceId', false);
			if(!$serviceid)
			{
				ajax::error('Something wen\'t wrong, and we didn\'t receive the data we expected. Please try again, or contact us if you think this is an error.');
			}
			$name = arr::get($_POST, 'name', false);
			if(!$name || empty($name))
			{
				ajax::error('We didn\'t get your name! Please reauth and share your name with us!');
			}
			$token = arr::get($_POST, 'accessToken', false);
			if(!$token)
			{
				ajax::error('Something wen\'t wrong, and we didn\'t receive the data we expected. Please try again, or contact us if you think this is an error.');
			}
			$secret = arr::get($_POST, 'signedRequest', false);
			if(!$secret)
			{
				ajax::error('Something wen\'t wrong, and we didn\'t receive the data we expected. Please try again, or contact us if you think this is an error.');
			}
			$existing = ORM::factory('Oauth')
				->where('type','=','facebook')
				->where('service_id','=',$serviceid)
				->find();
			if($existing->loaded())
			{
				// This is a re-auth. Just log the dude in?
				$existing->token = $token;
				$existing->token_secret = $secret;
				$existing->save();
				user::force_login($existing->user);
				ajax::success('You have been logged in. Welcome back '.$existing->user->username().'!');
			}
			else
			{
				$email = arr::get($_POST, 'email', false);
				// Did they share their email? Can we hook it up to an existing user?
				if($email && !empty($email))
				{
					// Check if there's an existing user
					$user = ORM::factory('User')
						->where('email', '=', $email)
						->find();
					if(!$user->loaded())
					{
						// New user
						$user->email = $email;
						$user->username = $name;
						$user->validation_required(false)->save();
						$user->add_role('login');
						$msg = 'You have signed up to Morning Pages! Welcome '.$name;
					}
					else
					{
						$msg = 'You can now log in with your Facebook account. Welcome back '.$user->username();
					}
				}
				else
				{
					// New user, email not shared
					$user->username = $name;
					$user->validation_required(false)->save();
					$user->add_role('login');
					$msg = 'You have signed up to Morning Pages! Welcome '.$name;
				}
				$oauth = ORM::factory('Oauth');
				$oauth->user_id = $user->id;
				$oauth->type = 'facebook';
				$oauth->token = $token;
				$oauth->token_secret = $secret;
				$oauth->service_id = $serviceid;
				$oauth->screen_name = $name;
				$oauth->save();
				ajax::success($msg);
			}
		}
		ajax::error('Something wen\'t wrong! I didn\'t receive any data. Please try again, or contact us if you think this is an error.');
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
	
	public function action_register()
	{
		if(user::logged())
		{
			ajax::error('You are already logged in as '.user::get()->username());
		}
		
		if($_POST)
		{
			$user = ORM::factory('User');
			try
			{
				user::create($_POST);
				if(user::logged())
				{
					ajax::success('You are now signed up. Welcome!');
				}
				else
				{
					// should log this error (user wasnt logged in with user::create())
					ajax::error('An error occurred and I couldn\'t sign you in! Please try again or open an issue here: https://github.com/ellenbrook/morningpages');
				}
			}
			catch(ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');
				$ehtml = '<ul>';
				foreach($errors as $error)
				{
					if(is_array($error)) foreach($error as $suberror)
					{
						$ehtml .= '<li>'.$suberror.'</li>';
					}
					else
					{
						$ehtml .= '<li>'.$error.'</li>';
					}
				}
				$ehtml .= '<ul>';
				ajax::error('Whoops! There was an error in the form. Please review it and submit it again.', array(
					'errors' => $ehtml
				));
			}
		}
		else
		{
			ajax::error('No data received');
		}
	}
	
	public function action_logout()
	{
		user::logout();
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
		$update_timestamp = false;
		switch($setting)
		{
			case 'reminder':
				$option->reminder = $value;
				$update_timestamp = true;
				break;
			case 'reminder_hour':
				$option->reminder_hour = $value;
				$update_timestamp = true;
				break;
			case 'reminder_minute':
				$option->reminder_minute = $value;
				$update_timestamp = true;
				break;
			case 'reminder_meridiem':
				$option->reminder_meridiem = $value;
				$update_timestamp = true;
				break;
			case 'timezone_id':
				$option->timezone_id = $value;
				$update_timestamp = true;
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
			case 'rtl':
				$option->rtl = $value;
				break;
			default:
				ajax::error('Something wen\'t wrong and your setting couldn\'t be saved. I received no data!');
				break;
		}
		try
		{
			if($update_timestamp)
			{
				$option->next_reminder = $user->get_next_reminder($user);
			}
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
	