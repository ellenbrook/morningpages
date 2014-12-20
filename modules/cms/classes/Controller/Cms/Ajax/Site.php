<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Site extends Controller {
	
	public function action_setup()
	{
		ajax::success('', array(
			'base' => Kohana::$config->load('site')->get('base')
		));
	}
	
	public function action_info()
	{
		maintenance::delete_inactive_visitors();
		
		$messages = 0;
		if(user::logged())
		{
			$user = user::get();
			$messages += $user->messages
				->where('read','=','0')
				->count_all();
			$roles = $user->roles->find_all();
			$roleids = array();
			if((bool)$roles->count())
			{
				foreach($roles as $role)
				{
					$roleids[] = $role->id;
				}
			}
			if((bool)count($roleids))
			{
				$messages += ORM::factory('Message')
					->where('role_id','in', $roleids)
					->where('read','=','0')
					->where('user_id','!=',$user->id)
					->count_all();
			}
		}
		
		ajax::success('',array(
			'current_visitors' => $visitors = ORM::factory('Visitor')->count_all(),
			'unread_messages' => $messages
		));
	}
	
}

