<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Games extends Controller {
	
	public function action_takechallenge()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in to sign up for the challenge!');
		}
		$user = user::get();
		if($user->doing_challenge())
		{
			ajax::error('You are already doing the challenge! Complete it first, then sign up again.');
		}
		$challenge = ORM::factory('User_Challenge');
		$challenge->user_id = $user->id;
		$challenge->start = $user->timestamp();
		$challenge->progress = 0;
		if($user->wrote_today())
		{
			$challenge->progress = 1;
		}
		$challenge->save();
		
		$user->add_event('Signed up for the 30 day challenge!');
		
		ajax::success('Awesome! You have signed up for the challenge! Good luck!', array(
			'progress' => $challenge->progress
		));
	}
	
}
	