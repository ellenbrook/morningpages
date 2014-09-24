<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Project {
	
	public $template = 'templates/frontpage';
	
	public function action_index(){}
	
	public function action_suggestions()
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
					->to('ericellenbrook@gmail.com')
					->content(arr::get($_POST, 'suggestion').'<br /><br />.E-mail: '.arr::get($_POST, 'email',''))
					->subject('Suggestions from '.site::option('sitename'))
					->send();
				site::redirect('suggestions');
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
