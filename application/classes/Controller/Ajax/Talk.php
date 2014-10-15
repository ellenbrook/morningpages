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
		$type = arr::get($_POST, 'type', false);
		if(!$type || ($type != 'talk' && $type != 'talkreply'))
		{
			ajax::error('Something wen\'t wrong and your vote could not be saved. Please try again or contact us if the problem persists.');
		}
		// Can we find the thing being voted on
		$id = arr::get($_POST, 'id', false);
		$model = ($type == 'talk' ? 'Talk' : 'Talkreply');
		$object = ORM::factory($model, $id);
		if(!$object->loaded())
		{
			ajax::error('I couldn\'t find that topic. Has it been deleted? Please contact us if you think this is a mistake');
		}
		// Has the user already voted on it (then remove it), or is it a new vote
		$vote = user::get()->votes
			->where('type','=',$type)
			->where($type.'_id','=',$object->id)
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
			$vote->type = $type;
			$vote->user_id = user::get()->id;
			if($type == 'talk')
			{
				$vote->talk_id = $object->id;
			}
			if($type == 'talkreply')
			{
				$vote->talkreply_id = $object->id; 
			}
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
	
}
	