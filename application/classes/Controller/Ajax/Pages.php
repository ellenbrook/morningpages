<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Pages extends Controller {
	
	public function action_autosave()
	{
		if(!user::logged())
		{
			ajax::error('User not logged in');
		}
		
		$page = ORM::factory('Page', arr::get($_POST, 'id',''));
		if(!$page->loaded() || user::get()->id != $page->user_id)
		{
			ajax::error('Page not found');
		}
		
		$content = arr::get($_POST, 'content','');
		
		$autosave = $page->get_autosave();
		if(!$autosave)
		{
			$autosave = ORM::factory('Page');
			$autosave->user_id = user::get()->id;
			$autosave->parent = $page->id;
			$autosave->type = 'autosave';
		}
		$autosave->content = $content;
		
		try
		{
			$autosave->autosave();
			//user::update_stats($content, $page);
			ajax::success('Page saved!');
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('Validation error');
			$errors = $e->errors('models');
		}
		
		ajax::info('Nothing to do');
	}
	
}
	