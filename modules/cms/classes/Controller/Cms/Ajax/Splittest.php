<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Splittest extends Controller {
	
	public function action_create()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'parent',''));
		if(!$content->loaded())
		{
			ajax::error('The content wasn\'t found. Has it been deleted?');
		}
		$title = arr::get($_POST, 'title',false);
		if(!$title)
		{
			$title = $content->title;
		}
		$clone = $content->copy();
		$clone->splittest = $content->id;
		$clone->splittest_title = $title;
		try
		{
			$clone->save();
			ajax::success('Splittest created',array(
				'splittest' => array(
					'id' => $clone->id,
					'title' => $clone->splittest_title
				)
			));
		}
		catch(exception $e)
		{
			ajax::error('An error occurred and the splittest couldn\'t be created. The site said: '.$e->getMessage());
		}
	}
	
}


