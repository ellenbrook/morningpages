<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Js extends Controller {
	
	public function action_vars()
	{
		$this->response->headers('Content-Type', 'application/javascript; charset=utf-8');
		echo 'url = "' . cms::url() . '/";';
		echo 'siteurl = "' . URL::site('/', true) . '/";';
		echo 'tinymceurl = "' . cms::url('media/libs/tiny_mce/tiny_mce.js') . '";';
		echo 'ajaxurl = "' . cms::url('ajax/', true) . '/";';
	}
	
}
