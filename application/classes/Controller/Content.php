<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Content extends Controller_Project {
	
	public function action_index()
	{
		$content = $this->request->param('content');
		$this->bind('page', $content);
	}
	
	/*
	 * Generic content with no controller/method defined
	 */
	public function action_default()
	{
		$content = $this->request->param('content');
		$this->bind('page', $content);
	}
	
	public function action_page()
	{
		$content = $this->request->param('content');
		$this->bind('page', $content);
	}
	
	public function action_blogpost()
	{
		$content = $this->request->param('content');
		$this->bind('post', $content);
	}
	
	public function action_socialresponse()
	{
		$content = $this->request->param('content');
		$this->bind('page', $content);
	}
	
}