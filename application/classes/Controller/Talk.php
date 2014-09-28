<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Talk extends Controller_Project {
	
	public function action_index()
	{
		$this->bind('tags', ORM::factory('Talktag')->find_all());
	}
	
	public function action_tag()
	{
		$tag = $this->request->param('tag');
		$errors = false;
		
		if($_POST)
		{
			$this->require_login();
			$talk = ORM::factory('Talk');
			$talk->values($_POST);
			$talk->user_id = user::get()->id;
			$talk->talktag_id = $tag->id;
			try
			{
				$talk->save();
				notes::success('Your talk has been created.');
				site::redirect($talk->url());
			}
			catch(ORM_Validation_Exception $e)
			{
				notes::error('Whoops! Your submission contained errors. Please review it and submit again');
				$errors = $e->errors();
			}
		}
		
		$this->bind('tag', $tag);
		$this->bind('errors', $errors);
		$this->bind('tags', ORM::factory('Talktag')->find_all());
	}
	
	public function action_talk()
	{
		$tag = $this->request->param('tag');
		$talk = $this->request->param('talk');
		
		if($_POST)
		{
			$this->require_login();
			$reply = ORM::factory('Talkreply');
			$reply->values($_POST);
			$reply->user_id = user::get()->id;
			$reply->talk_id = $talk->id;
			try
			{
				$reply->save();
				notes::success('Your reply has been posted.');
				site::redirect($talk->url());
			}
			catch(ORM_Validation_Exception $e)
			{
				notes::error('Whoops! Your submission contained errors. Please review it and submit again');
				$errors = $e->errors();
			}
		}
		
		$this->bind('tag', $tag);
		$this->bind('talk', $talk);
		$this->bind('tags', ORM::factory('Talktag')->find_all());
	}

}
