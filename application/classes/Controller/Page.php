<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
	public function before()
	{
		$this->require_login();
		return parent::before();
	}
	
	public function action_daynotfound() {}
	
	public function action_write()
	{
		$errors = false;
		$page = $this->request->param('page');
		if($_POST && strlen(arr::get($_POST, 'morningpage',''))>0)
		{
			$content = arr::get($_POST, 'morningpage','');
			$page->content = $page->content().$content;
			try
			{
				$page->update();
				$autosave = $page->get_autosave();
				if($autosave)
				{
					$autosave->delete();
				}
				if(!(bool)$page->counted)
				{
					user::update_stats($content, $page);
					$page->counted = 1;
					$page->save();
				}
				notes::success('Your page has been saved!');
				site::redirect('write/'.$page->day);
			}
			catch(ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');
			}
		}
		$this->bind('errors', $errors);
		$this->bind('page', $page);
		$this->template->page = $page;
	}
	
}
