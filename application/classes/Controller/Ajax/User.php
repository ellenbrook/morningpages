<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_User extends Controller {
	
	public function action_info()
	{
		if(!user::logged())
		{
			ajax::error('User not logged in');
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
			'theme' => $user->theme,
			'reminder' => $user->reminder,
			'wordcount' => $wordcount
		));
	}
	
	public function action_saveInfo()
	{
		if(!user::logged())
		{
			ajax::error('User not logged in');
		}
		$user = user::get();
		
		try
		{
			if(arr::get($_POST, 'reminder',false) == "true")
			{
				$_POST['reminder'] = 1;
			}
			else
			{
				$_POST['reminder'] = 0;
			}
			$user->update_user($_POST,array(
				'email',
				'theme',
				'reminder',
				'password'
			));
			ajax::success('Your user information has been updated');
		}
		catch(exception $e)
		{
			ajax::error($e->getMessage());
			//ajax::error('An error occurred and your information could not be saved. If this keeps happening please contact us so we can get this fixed! Thanks');
			Log::adderror('Couldn\'t save user info: '.$e->getMessage());
		}
		
	}
	
}
	