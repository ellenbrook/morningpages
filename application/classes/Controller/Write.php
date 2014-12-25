<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Write extends Controller_Project {
	
	public function action_daynotfound() {}
	
	public function action_write()
	{
		$errors = false;
		$page = false;
		if($_POST && !user::logged())
		{
			notes::error('You must be logged in to save your page. Please log in and submit again.');
		}
		if(user::logged())
		{
			$page = $this->request->param('page');
			
			if($_POST && strlen(arr::get($_POST, 'content',''))>0)
			{
				if(user::logged())
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
						$page->wordcount = count(preg_split('/[\s,.]+/u', $content));
						$page->content = $content;
						
						$user = user::get();
						$yesterday_slug = site::day_slug(strtotime('-1 day',$user->timestamp()));
						$two_days_ago_slug = site::day_slug(strtotime('-2 days',$user->timestamp()));
						$points = 0;
						if($page->wordcount >= 750)
						{
							if(!(bool)$page->counted)
							{
								user::update_stats($page);
								$page->counted = 1;
							}
							
							// Calculating points
							$points = 2;
							$prevpages = $user->pages
								->where('type','=','page')
								->and_where_open()
								->where('day','=',$yesterday_slug)
								->or_where('day','=',$two_days_ago_slug)
								->and_where_close()
								->find_all();
							foreach($prevpages as $prev)
							{
								if($prev->wordcount >= 750)
								{
									$points += 2;
								}
								else if ($prev->wordcount >= 100)
								{
									$points += 1;
								}
							}
						}
						else if ($page->wordcount >= 100)
						{
							$points = 1;
							$yesterday = $user->pages
								->where('type','=','page')
								->and_where('day','=',$yesterday_slug)
								->find();
							if($yesterday->loaded())
							{
								if($yesterday->wordcount >= 750)
								{
									$points += 2;
								}
								else if ($yesterday->wordcount >= 100)
								{
									$points += 1;
								}
							}
						}
						$page->points = $points;
						
						$page->duration = $page->duration + (time() - arr::get($_POST, 'start', 999));
						$page->rid = ''; // resetting the RID as it needs to be recalculated when the page is updated
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

		seo::instance()->title("Write Your Morning Pages");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
	}
	
	public function action_pagestats()
	{
		seo::instance()->title("Page stats");
		$this->bind('page',$this->request->param('page'));
	}
	
}
