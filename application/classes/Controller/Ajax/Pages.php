<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Pages extends Controller {
	
	public function action_autosave()
	{
		if(!user::logged())
		{
			ajax::error('User not logged in');
		}
		
        $user = user::get();
		$autosave = ORM::factory('Page')
            ->where('user_id','=', $user->id)
            ->where('type','=','autosave')
            ->find();
		if(!$autosave->loaded())
		{
			$autosave->user_id = $user->id;
            $autosave->type = 'autosave';
		}
		
		$content = arr::get($_POST, 'content','');
		$autosave->content = $content;
		
		try
		{
			$autosave->save();
			ajax::success('Page saved!');
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('Validation error');
			$errors = $e->errors('models');
		}
		
		ajax::info('Nothing to do');
	}
	
	public function action_getautosave()
	{
		if(!user::logged())
		{
			ajax::error('User not logged in');
		}
		$user = user::get();
		$autosave = ORM::factory('Page')
            ->where('user_id','=', $user->id)
            ->where('type','=','autosave')
            ->find();
		$content = '';
		if($autosave->loaded() && $content != '')
		{
			$content = $autosave->decode($autosave->content);
		}
		ajax::success('',array(
			'content' => $content
		));
	}
	
}
	