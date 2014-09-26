<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
	public function action_daynotfound() {}
	
	public function action_anonymouswriting()
	{
		$this->template->view = View::factory('Page/write');
	}
	
	public function action_write()
	{
		$errors = false;
		$page = false;
		if(user::logged())
		{
			$page = $this->request->param('page');
			
			if($_POST && strlen(arr::get($_POST, 'morningpage',''))>0)
			{
				if(user::logged())
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
				else
				{
					notes::error('You must be logged in to save your page.');
				}	
			}
		}
		$this->bind('errors', $errors);
		$this->bind('page', $page);
        $this->template->daystamp = $this->request->param('daystamp');
		$this->template->page = $page;
	}
	public function action_options()
	{
		
	}
}
