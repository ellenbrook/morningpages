<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Talk extends Controller_Project {
	
	private $pagination_limit = 200;
	
	public function action_index()
	{
		$tag = $this->request->param('tag');
		$errors = false;
		
		if($_POST)
		{
			$this->require_login();
			$talk = ORM::factory('Talk');
			$talk->values($_POST);
			$talk->user_id = user::get()->id;
			if($tag && $tag->loaded())
			{
				$talk->talktag_id = $tag->id;
			}
			try
			{
				$talk->save();
				$reply = ORM::factory('Talkreply');
				$reply->values($_POST);
				$reply->user_id = user::get()->id;
				$reply->talk_id = $talk->id;
				$reply->op = 1;
				try
				{
					$reply->save();
					$vote = ORM::factory('User_Talkvote');
					$vote->type = 'talkreply';
					$vote->user_id = user::get()->id;
					$vote->object_id = $reply->id;
					$vote->save();
					notes::success('Your talk has been created.');
					site::redirect($talk->url());
				}
				catch(ORM_Validation_Exception $e)
				{
					notes::error('Whoops! Your submission contained errors. Please review it and submit again');
					$errors = $e->errors('models');
				}
			}
			catch(ORM_Validation_Exception $e)
			{
				notes::error('Whoops! Your submission contained errors. Please review it and submit again');
				$errors = $e->errors('models');
			}
		}
		
		$talks = ORM::factory('Talk');
		$counter = ORM::factory('Talk');
		if($tag && $tag->loaded())
		{
			$talks = $talks->where('talktag_id','=',$tag->id);
			$counter = $counter->where('talktag_id','=',$tag->id);
		}
		$limit = $this->pagination_limit;
		$numtalks = $counter->count_all();
		
		$numpages = ceil($numtalks/$limit);
		$page = (int)arr::get($_GET, 'page',0);
		if($page < 1)
		{
			$page = 1;
		}
		if($page > $numpages)
		{
			$page = $numpages;
		}
		
		$talks = $talks->limit($limit);
		if($page-1 > 0)
		{
			$talks = $talks->offset($limit*($page-1));
		}
		$talks = $talks->find_all();
		
		$this->bind('tag', $tag);
		$this->bind('errors', $errors);
		$this->bind('tags', ORM::factory('Talktag')->find_all());
		$this->bind('talks',$talks);
		$this->bind('numpages', $numpages);
		$this->bind('currentpage', $page);
	}
	
	public function action_talknotfound()
	{
		$tag = $this->request->param('tag');
		notes::error('We couldnt find that discussion. Sorry!');
		site::redirect($tag->url());
	}
	
	public function action_tagnotfound()
	{
		notes::error('We couldnt find that topic. Sorry!');
		site::redirect('talk');
	}
	
	public function action_talk()
	{
		$tag = $this->request->param('tag');
		$talk = $this->request->param('talk');
		if(user::logged())
		{
			if($talk->user_id != user::get()->id)
			{
				$talk->views = $talk->views+1;
				$talk->save();
			}
		}
		
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
				$vote = ORM::factory('User_Talkvote');
				$vote->type = 'talkreply';
				$vote->user_id = user::get()->id;
				$vote->object_id = $reply->id;
				$vote->save();
				notes::success('Your reply has been posted.');
				site::redirect($talk->url());
			}
			catch(ORM_Validation_Exception $e)
			{
				notes::error('Whoops! Your submission contained errors. Please review it and submit again');
				$errors = $e->errors();
			}
		}
		$replies = $talk->replies
			->where('op','!=',1);
		$counter = $talk->replies
			->where('op','!=',1);
		$limit = $this->pagination_limit;
		$numreplies = $counter->count_all();
		
		$numpages = ceil($numreplies/$limit);
		$page = (int)arr::get($_GET, 'page',0);
		if($page < 1)
		{
			$page = 1;
		}
		if($page > $numpages)
		{
			$page = $numpages;
		}
		
		$replies = $replies->limit($limit);
		if($page-1 > 0)
		{
			$replies = $replies->offset($limit*($page-1));
		}
		$replies = $replies->find_all();
		
		$this->bind('tag', $tag);
		$this->bind('talk', $talk);
		$this->bind('replies', $replies);
		$this->bind('tags', ORM::factory('Talktag')->find_all());
		$this->bind('numpages', $numpages);
		$this->bind('currentpage', $page);
	}

}
