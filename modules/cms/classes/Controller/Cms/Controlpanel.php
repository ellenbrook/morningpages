<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Controlpanel extends Controller {
	
	public function action_index()
	{
		reply::ok(View::factory('Cms/Controlpanel/index'), 'welcome', array(
			'viewModel' => 'viewModels/Controlpanel/index'
		));
	}
	
}
