<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
	public function action_daynotfound() {}
	
	public function action_anonymouswriting()
	{
		$this->template->view = View::factory('Page/write');
	}
	
	public function action_about()
	{
		seo::instance()->title("About Morning Pages");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
	}
	
	public function action_terms()
	{
		seo::instance()->title("Terms of Service");
		seo::instance()->description("Morning Pages terms of service.");
	}
	
	public function action_privacy()
	{
		seo::instance()->title("Morning Pages Privacy Policy");
		seo::instance()->description("Morning Pages Privacy Policy");
	}
	
	public function action_challenge()
	{
		seo::instance()->title("Take the 30 day writing challenge");
		seo::instance()->description("Take the 30 day Morning Page writing challenge, and improve yourself!");
	}
	
	public function action_walloffame()
	{
		seo::instance()->title("30 day writing challenge hall of fame!");
		seo::instance()->description("These users are the proud completers of the 30 day Morning Page challenge!");
	}
	
	public function action_leaderboard()
	{
		$active = DB::query(Database::SELECT, "
	        SELECT users.id, COUNT(talkreplies.user_id) as posts
	        FROM talkreplies
	        LEFT JOIN users ON users.id = talkreplies.user_id
	        GROUP BY talkreplies.user_id
	        ORDER BY posts DESC
	        LIMIT 10
	    ")->execute()->as_array();
		
		$users = ORM::factory('User')
			->order_by('current_streak', 'DESC')
			->limit(10)
			->find_all();
		
		seo::instance()->title("Morning Pages leaderboard");
		seo::instance()->description("Morning Pages leaderboard");
		
		$this->bind('active', $active);
		$this->bind('users', $users);
	}
	
	public function action_write()
	{
		$errors = false;
		$page = false;
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
						else
						{
							$content = $content;
						}
					}
					try
					{
		                if($page->type == 'autosave')
		                {
		                    $page->type = 'page';
		                }
		                
						$page->wordcount = count(preg_split('/[\s,.]+/u', $content));
						$page->content = $content;
						if($page->wordcount > 750 && !(bool)$page->counted)
						{
							user::update_stats($content, $page);
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

		seo::instance()->title("Write Your Morning Pages");
		seo::instance()->description("Morning Pages is about writing three pages of stream of consciousness thought every day. Become a better person by using MorninPages.net");
	}
	
	public function action_contact()
	{
		$errors = false;
		if($_POST)
		{
			$val = Validation::factory($_POST);
			$val->rule('sprot', 'exact_length', array(':value', 1));
			$val->rule('email', 'not_empty');
			$val->rule('email', 'email');
			$val->rule('suggestion', 'not_empty');
			if($val->check())
			{
				
				notes::success('Your message has been sent and we will get back to you as soon as possible. Thanks!');
				$mail = mail::create('suggestion')
					->to('morningpagesnet@gmail.com')
					->content(arr::get($_POST, 'suggestion').'<br /><br />.E-mail: '.arr::get($_POST, 'email',''))
					->subject('Suggestions from '.site::option('sitename'))
					->send();
				site::redirect('contact');
			}
			else
			{
				$errors = $val->errors('suggestions');
			}
		}
		$this->bind('errors',$errors);

		seo::instance()->title("Contact Morning Pages");
		seo::instance()->description("Feel free to contact MorningPages.net if you have questions or concerns about your account, the site or for more information regarding your Morning Pages.");
	}
	
	public function action_faq()
	{
		seo::instance()->title("Morning Pages Questions");
		seo::instance()->description("Frequently asked questions regarding your Morning Pages, the Morning Pages website, and more. Don't see your listed? Check the Morning Pages forum for more info.");
	}
	
}
