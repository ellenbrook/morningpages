<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Mail extends Controller {
	
	public function action_show()
	{
		$token = $this->request->param('id');
		$mail = ORM::factory('Mail')->where('token', '=', $token)->find();
		if($mail->loaded())
		{
			$view = View::factory('templates/mail');
			$view->mail = $mail;
			echo $view;
		}
		else
		{
			notes::add('error', 'Mail not found!');
			site::redirect('');
			die();
		}
	}
	
}
