<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
	public $template = 'templates/write';
	
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
			if($page->type == 'page')
			{
				$page->content = $page->content().$content;
			}
			try
			{
			    $page->wordcount = str_word_count(strip_tags($page->content()));
                if($page->type == 'autosave')
                {
                    $page->type = 'page';
                }
				if($page->wordcount > 750 && !(bool)$page->counted)
				{
					user::update_stats($content, $page);
					$page->counted = 1;
				}
				$page->update();
				/*$autosave = $page->get_autosave();
				if($autosave)
				{
					$autosave->delete();
				}				
				if(!(bool)$page->counted)
				{
					
				}
                $page->save();*/
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
        $this->template->daystamp = $this->request->param('daystamp');
		$this->template->page = $page;
	}
	
}
