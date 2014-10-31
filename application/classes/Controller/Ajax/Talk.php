<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Talk extends Controller {
	
	public function action_vote()
	{
		// Is the user logged in
		if(!user::logged())
		{
			ajax::error('You must be logged in to do that.');
		}
		// Is the type correct
		// Can we find the thing being voted on
		$id = arr::get($_POST, 'id', false);
		$object = ORM::factory('Talkreply', $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that topic. Has it been deleted? Please contact us if you think this is a mistake');
		}
		// Has the user already voted on it (then remove it), or is it a new vote
		$vote = user::get()->votes
			->where('type','=','talkreply')
			->where('object_id','=',$object->id)
			->where('user_id','=',user::get()->id)
			->find();
		if($vote->loaded())
		{
			// Yep, remove the vote
			try
			{
				$vote->delete();
				ajax::success('remove');
			}
			catch(exception $e)
			{
				ajax::error('An error occurred and your vote couldn\'t be removed. Please contact us if the problem persists');
			}
		}
		else
		{
			// Nope, vote
			$vote->type = 'talkreply';
			$vote->user_id = user::get()->id;
			$vote->object_id = $object->id;
			try
			{
				$vote->save();
				ajax::success('add');
			}
			catch(exception $e)
			{
				ajax::error('An error occurred and your vote couldn\'t be added. Please contact us if the problem persists');
			}
		}
	}
	
	public function action_quoteform()
	{
		// Is the user logged in
		if(!user::logged())
		{
			ajax::error('You must be logged in to do that.');
		}
		// Can we find the thing being quoted?
		$id = arr::get($_GET, 'id', false);
		$object = ORM::factory('Talkreply', $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that topic. Has it been deleted? Please contact us if you think this is a mistake');
		}
		ajax::success('',array(
			'quote' => $object->quote()
		));
	}
	
	public function action_rawform()
	{
		// Is the user logged in
		if(!user::logged())
		{
			ajax::error('You must be logged in to do that.');
		}
		// Can we find the thing being edited?
		$id = arr::get($_GET, 'id', false);
		$object = ORM::factory('Talkreply', $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that topic. Has it been deleted? Please contact us if you think this is a mistake');
		}
		if(!user::can_edit($object))
		{
			ajax::error('That doesn\'t seem to be your post to edit. Please contact us if you think this is a mistake');
		}
		ajax::success('',array(
			'raw' => $object->raw()
		));
	}
	
	public function action_edit()
	{
		// Is the user logged in
		if(!user::logged())
		{
			ajax::error('You must be logged in to do that.');
		}
		// Can we find the thing being edited?
		$id = arr::get($_POST, 'id', false);
		$object = ORM::factory('Talkreply', $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that post. Has it been deleted? Please contact us if you think this is a mistake');
		}
		if(!user::can_edit($object))
		{
			ajax::error('That doesn\'t seem to be your post to edit. Please contact us if you think this is a mistake');
		}
		$content = arr::get($_POST, 'value', false);
		if(!$content)
		{
			ajax::error('You can\'t post an empty message.');
		}
		$object->content = $content;
		$object->edited = time();
		try
		{
			$object->save();
			ajax::success('Saved', array(
				'post' => $object->content()
			));
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('Your message is either too long (max 1000 characters) or too short (min 1 character)');
		}
		catch(exception $e)
		{
			ajax::error('Something wen\'t wrong. Please try again or contact us if the problem persists.');
		}
	}
	
	public function action_deletepost()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in to do that.');
		}
		$id = arr::get($_POST, 'id', false);
		$object = ORM::factory('Talkreply', $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that post. Has it already been deleted? Please contact us if you think this is a mistake');
		}
		if(!user::can_edit($object))
		{
			ajax::error('That doesn\'t seem to be your post to delete. Please contact us if you think this is a mistake');
		}
		$object->deleted = time();
		$object->deleted_by = user::get()->id;
		try
		{
			$object->talk->deleted = 0;
			$object->talk->save();
			$object->save();
			ajax::info('Your post has been deleted.');
		}
		catch(exception $e)
		{
			Kohana::$log->add(Log::CRITICAL, 'Couldn\'t delete Model_Talkreply: :message. User_id: :userid, postreply_id: :replyid', array(
				':message' => $e->getMessage(),
				':userid' => user::get()->id,
				':replyid' => $object->id
			));
			ajax::error('Something went wrong and your post couln\'t be deleted. Please try again or contact us if you think this is a mistake.');
		}
	}
	
}
	