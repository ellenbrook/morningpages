<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Export extends Controller {
	
	public function action_doit()
	{
		$this->response->send_file(URL::site('ajax/export/xml'));
	}
	
	public function action_xml()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in to use this feature');
		}
		
		$user = user::get();
		$pages = $user->pages->where('type','=','page')->find_all();
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<channel>';
		$namelen = strlen($user->username);
		$possessive = $user->username."'s";
		if (substr($user->username, $namelen-1, $namelen) == 's')
		{
			$possessive = $user->username."'";
		}
		$xml .= '<title>'.$possessive.' morning pages</title>';
		$xml .= '<language>en-US</language>';
		$xml .= '<author>'.$user->username.'</author>';
		if((bool)$pages->count())
		{
			foreach($pages as $page)
			{
				$xml .= '<item>';
				$xml .= '<published>';
				$xml .= '<date>'.$page->daystamp().'</date>';
				$xml .= '<timestamp>'.$page->created.'</timestamp>';
				$xml .= '</published>';
				$xml .= '<content><![CDATA['.$page->content().']]></content>';
				$xml .= '</item>';
			}
		}
		$xml .= '</channel>';
		$this->response->headers('Content-Type','text/xml');
		echo $xml;
	}
	
}
	