<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller {
	
	private $creds;
	
	public function before()
	{
		parent::before();
		Kohana::load(Kohana::find_file('vendor/twitteroauth', 'twitteroauth'));
		$this->creds = Kohana::$config->load('secrets')->get('twitter');
	}
	
	public function action_twitter()
	{
		$redirect = '';
		try
		{
			$connection = new TwitterOAuth(arr::get($this->creds, 'key'), arr::get($this->creds, 'secret'));
			$tmp = $connection->getRequestToken('http://morningpages.net/auth/twittercallback');
			Session::instance()->set('twitter_oauth_token', arr::get($tmp, 'oauth_token',''));
			Session::instance()->set('twitter_oauth_token_secret', arr::get($tmp, 'oauth_token_secret',''));
			$redirect = $connection->getAuthorizeURL($tmp);
		}
		catch(exception $e)
		{
			ajax::error('Oh no! Something went wrong and we couldn\'t get a hold of Twitter! They might be too busy right now. You can either wait a bit and see if Twitter wakes up or use another way of logging in.');
			site::redirect();
		}
		site::redirect($redirect);
	}
	
	public function action_twittercallback()
	{
		if(arr::get($_GET, 'denied', false))
		{
			notes::error('Seems like you didn\'t want to log in with Twitter anyway. Feel free to try again if it was a mistake!');
			site::redirect();
		}
		
		$token = arr::get($_GET, 'oauth_token', false);
		$verifier = arr::get($_GET, 'oauth_verifier', false);
		
		if(!$token || !$verifier)
		{
			notes::error('Something went wrong in the process, and we didn\'t get the expected data back from Twitter. Please try again');
			site::redirect();
		}
		
		$connection = new TwitterOAuth(
			arr::get($this->creds, 'key'),
			arr::get($this->creds, 'secret'),
			Session::instance()->get_once('twitter_oauth_token'),
			Session::instance()->get_once('twitter_oauth_token_secret')
		);
		$token = $connection->getAccessToken($verifier);
		
		$oauth_token = arr::get($token, 'oauth_token', '');
		$oauth_token_secret = arr::get($token, 'oauth_token_secret', '');
		$user_id = arr::get($token, 'user_id', '');
		$screen_name = arr::get($token, 'screen_name', '');
		
		$oauth = ORM::factory('Oauth')
			->where('type','=','twitter')
			->where('token','=', $oauth_token)
			->find();
		if($oauth->loaded())
		{
			try
			{
				$user = $oauth->user;
				user::force_login($user);
			}
			catch(exception $e)
			{
				if($user->loaded())
				{
					if(user::logged())
					{
						// Random error, but user got logged in. We don't care, YOLO!
					}
					else
					{
						notes::error('Whoops! Something wen\'t wrong and we couldn\'t log you in. Please try again or send us a message if the problem persists.');
					}
				}
			}
			site::redirect('write');
		}
		else
		{
			try
			{
				$user = ORM::factory('User');
				$user->username = $screen_name;
				$user->validation_required(false)->save();
				$user->add_role('login');
				
				$oauth = ORM::factory('Oauth');
				$oauth->user_id = $user->id;
				$oauth->type = 'twitter';
				$oauth->token = $oauth_token;
				$oauth->token_secret = $oauth_token_secret;
				$oauth->service_id = $user_id;
				$oauth->screen_name = $screen_name;
				$oauth->save();
				
				user::force_login($user);
			}
			catch(exception $e)
			{
				notes::error('Whoops! Something wen\'t wrong and we couldn\'t log you in. Please try again or send us a message if the problem persists.');
			}
			
			site::redirect('/write');
			
		}
		
	}
	
}
