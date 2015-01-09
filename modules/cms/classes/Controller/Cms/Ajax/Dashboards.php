<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Dashboards extends Controller {
	
	public function action_current()
	{
		ajax::success('ok',dashboards::get_current()->info());
	}
	
	public function action_deletewidget()
	{
		$widget = ORM::factory('Widget', arr::get($_POST, 'id',false));
		if(!$widget->loaded())
		{
			ajax::error('Indholdet blev ikke fundet. Er den blevet slettet i mellemtiden?');
		}
		try
		{
			$widget->delete();
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error('Der opstod en fejl og den valgte widget kunne ikke slettes: '.$e->getMessage());
		}
	}
	
	public function action_addwidget()
	{
		$widget = ORM::factory('Widget');
		try
		{
			$widget->widgettype_id = arr::get($_POST, 'widgettype');
			$widget->dashboard_id = arr::get($_POST, 'dashboard');
			$widget->save();
			ajax::success('',$widget->info());
		}
		catch(exception $e)
		{
			ajax:error('Der opstod en fejl og din widget kunne ikke tilføjes: '.$e->getMessage());
		}
	}
	
	public function action_savewidgetsorder()
	{
		$data = $_POST;
		try
		{
			foreach($data as $id => $order)
			{
				$type = ORM::factory('Widget',$id);
				if($type->loaded())
				{
					$type->order = $order;
					$type->save();
				}
			}
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error($e->getMessage().', File: '.$e->getFile().', Line: '.$e->getLine());
		}
	}
	
	public function action_create()
	{
		if($_POST)
		{
			$dashboard = ORM::factory('Dashboard');
			$dashboard->user_id = user::get()->id;
			$dashboard->name = arr::get($_POST, 'name', 'Default');
			$dashboard->order = ORM::factory('Dashboard')->where('user_id','=',user::get()->id)->count_all();
			try
			{
				$dashboard->save();
				$dashboard->setcurrent();
				ajax::success('Created',$dashboard->info());
			}
			catch(ORM_Validation_Exception $e)
			{
				ajax::error('Validation error', array('errors' => $e->errors()));
			}
		}
		ajax::error('No data received');
	}
	
	public function action_all()
	{
		$dashboards = user::get()->dashboards->find_all();
		$darray();
		foreach($dashboards as $dashboard)
		{
			$darray[] = $dashboard->info();
		}
		ajax::success('', array(
			'dashboards' => $darray
		));
	}
	
	public function action_get()
	{
		$dashboard = ORM::factory('Dashboard', arr::get($_POST, 'id'));
		if(!$dashboard->loaded())
		{
			$dashboard = dashboards::get_current();
		}
		else
		{
			$dashboard->setcurrent();
		}
		ajax::success('ok', array(
			'dashboard' => $dashboard->info()
		));
	}
	
	public function action_getall()
	{
		$dbs = array();
		$dashboards = ORM::factory('Dashboard')->find_all();
		if((bool)$dashboards->count())
		{
			foreach($dashboards as $dashboard)
			{
				$dbs[] = $dashboard->info();
			}
		}
		ajax::success('ok', array(
			'dashboards' => $dbs
		));
	}
	
	public function action_delete()
	{
		$dashboard = ORM::factory('Dashboard', arr::get($_POST, 'id'));
		if(!$dashboard->loaded())
		{
			ajax::error('The dashboard wasn\'t found. Has it been deleted in the meantime?');
		}
		try
		{
			$user = $dashboard->user;
			$dashboard->delete();
			$dashboards = $user->dashboards->count_all();
			if((bool)$dashboards)
			{
				$current = $user->dashboards->find();
				$current->current = 1;
				$current->save();
			}
			ajax::info('Dashboard deleted', array(
				'dashboard' => dashboards::get_current()->info()
			));
		}
		catch(exception $e)
		{
			ajax::error('An error occurred and the panel could not be deleted: '.$e->getMessage());
		}
	}
	
	public function action_getwidgets()
	{
		$dashboard = ORM::factory('Dashboard', arr::get($_POST, 'id'));
		if(!$dashboard->loaded())
		{
			ajax::error('he dashboard wasn\'t found. Has it been deleted in the meantime?');
		}
		$widgets = $dashboard->widgets->find_all();
		$warr = array();
		$view = View::factory('Cms/Cms/widgets/template');
		if((bool)$widgets->count())
		{
			foreach($widgets as $widget)
			{
				$v = $view;
				$v->widget = $widget;
				$warr[] = array(
					'element' => $v->render(),
					'sizex' => $widget->sizex,
					'sizey' => $widget->sizey,
					'col' => $widget->col,
					'row' => $widget->row,
					'type' => $widget->widgettype->type
				);
			}
		}
		ajax::success('ok', array('widgets' => $warr));
	}
	
	public function action_updateWidgets()
	{
		$dashboard = ORM::factory('Dashboard', arr::get($_POST, 'id'));
		if(!$dashboard->loaded())
		{
			ajax::error('he dashboard wasn\'t found. Has it been deleted in the meantime?');
		}
		$widgets = arr::get($_POST, 'widgets',false);
		if($widgets && (bool)count($widgets))
		{
			foreach($widgets as $w)
			{
				$widget = ORM::factory('Widget',arr::get($w, 'id'));
				if($widget->loaded())
				{
					$widget->sizex = arr::get($w, 'sizex');
					$widget->sizey = arr::get($w, 'sizey');
					$widget->col = arr::get($w, 'col');
					$widget->row = arr::get($w, 'row');
					$widget->save();
				}
			}
		}
		ajax::success('ok');
	}
	
}
