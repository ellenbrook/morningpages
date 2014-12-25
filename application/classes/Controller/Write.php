<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Write extends Controller_Project {
	
	public function action_daynotfound() {}
	
	public function action_write()
	{
		$errors = false;
		$page = false;
		
		if(user::logged())
		{
			$page = $this->request->param('page');
			
			if($_POST && strlen(arr::get($_POST, 'content',''))>0)
			{
				$content = arr::get($_POST, 'content','');
				
				if($page->type == 'page')
				{
					$raw = $page->rawcontent();
					if($raw != "")
					{
						$content = $raw."\n".$content;
					}
				}
				else if($page->type == 'autosave')
				{
					$page->type = 'page';
				}
				
				try
				{
					$page->wordcount = site::count_words($content);
					$page->content = $content;
					
					if($page->wordcount >= 750 && !(bool)$page->counted)
					{
						user::update_stats($page);
						$page->counted = 1;
					}
					
					$page->duration = $page->duration + (time() - arr::get($_POST, 'start', 999));
					
					$page->update();
					
					$oldsaves = ORM::factory('Page')
						->where('type','=','autosave')
						->where('user_id','=',user::get()->id)
						->find_all();
					if((bool)$oldsaves->count())
					{
						foreach($oldsaves as $old)
						{
							$old->delete();
						}
					}
					
					achievement::check_all(user::get());
					
					notes::success('Your page has been saved!');
					//site::redirect('write/'.$page->day);
				}
				catch(ORM_Validation_Exception $e)
				{
					$errors = $e->errors('models');
				}
			}
		}
		else
		{
			if($_POST)
			{
				notes::error('You must be logged in to save your page. Please log in and submit again.');
			}
		}
		
		$this->bind('errors', $errors);
		$this->bind('page', $page);
        $this->template->daystamp = $this->request->param('daystamp');
		$this->template->page = $page;

		seo::instance()->title("Write Your Morning Pages");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
	}
	
	public function action_pagestats()
	{
		seo::instance()->title("Page stats");
		$this->bind('page',$this->request->param('page'));
	}
	
}
