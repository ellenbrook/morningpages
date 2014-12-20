<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Options extends Controller {
	
	public function action_index()
	{
		$view = View::factory('Cms/Options/index');
		reply::ok($view, 'options', array(
			'viewModel' => 'viewModels/Options/index'
		));
	}
	
}
