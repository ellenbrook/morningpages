<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
	public function action_daynotfound() {}
	
	public function action_anonymouswriting()
	{
		$this->template->view = View::factory('Page/write');
	}
	
	public function action_about(){}
	
	public function action_terms(){}
	
	public function action_privacy(){}
	
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
						$page->content = $page->content().$content;
					}
					try
					{
		                if($page->type == 'autosave')
		                {
		                    $page->type = 'page';
							$page->content = $content;
		                }
						$page->wordcount = str_word_count(strip_tags($page->content()));
						if($page->wordcount > 750 && !(bool)$page->counted)
						{
							user::update_stats($content, $page);
							$page->counted = 1;
						}
						$page->update();
						achievement::check_all(user::get());
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
	}
	
	public function action_faq(){}
	
}
