<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Project {
	
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
	
	public function action_contact()
	{
		notes::achievement('Congratulations! You earned the achievement "WHATEVER", and banked 10 points! Great job!');
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
