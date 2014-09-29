<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Project extends Controller_Template {
	
	public $template = 'templates/default';
	
	protected $view;
	protected $scripts = array();
	protected $css = array();
	
	protected function view($title)
	{
		$this->template->view = View::factory($title);
	}
	
	public function before()
	{
		parent::before();
		if($this->request->action() != 'media')
		{
			$this->template->controller = $this->request->controller();
			$this->template->action = $this->request->action();
			$foldercontroller = $this->template->controller;
			$foldercontroller = str_replace('_','/',$foldercontroller);
			$file = $foldercontroller.'/'.$this->template->action;
			if((file_exists(Kohana::find_file('views', $file))))
			{
				$this->template->view = View::factory($file);
			}
		}
	}
	
	public function after()
	{
		if(is_object($this->template))
		{
			$this->template->scripts = $this->scripts;
			$this->template->css = $this->css;
		}
		parent::after();
	}
	
	public function bind($key, $val)
	{
		$this->template->view->$key = $val;
	}
	
	public function load_script($script)
	{
		$this->scripts[] = $script;
	}
	
	public function load_css($file)
	{
		$this->css[] = $file;
	}
	
	public function require_login($msg = true, $redirect = false)
	{
		if($msg === true)
		{
			$msg = 'You must be logged in to see this page';
		}
		if(!user::logged())
		{
			if($msg)
			{
				notes::error($msg);
			}
			if($redirect)
			{
				site::redirect($redirect);
			}
			else
			{
				user::redirect('login');
			}
		}
	}

}
