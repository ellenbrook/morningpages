<?php

abstract class dashboards {
	
	public static function get_current()
	{
		$dashboard = ORM::factory('Dashboard')
			->where('user_id','=',user::get()->id)
			->where('current','=','1')
			->find();
		if(!$dashboard->loaded())
		{
			$dashboard->user_id = user::get()->id;
			$dashboard->current = 1;
			$dashboard->order = 0;
			$dashboard->name = 'Default';
			$dashboard->save();
		}
		return $dashboard;
	}
	
}
