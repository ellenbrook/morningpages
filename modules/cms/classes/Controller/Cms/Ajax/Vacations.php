<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Vacations extends Controller {
	
	public function action_add()
	{
		$vacation = ORM::factory('Vacation');
		$vacation->title = arr::get($_POST, 'title','');
		$start = arr::get($_POST, 'start','');
		list($startday,$startmonth,$startyear) = explode('-', $start);
		$vacation->start = mktime(0,0,0,$startmonth,$startday,$startyear);
		$end = arr::get($_POST, 'end','');
		list($endday,$endmonth,$endyear) = explode('-', $end);
		$vacation->end = mktime(0,0,0,$endmonth,$endday,$endyear);
		try
		{
			$vacation->save();
			ajax::success('',array(
				'vacation' => $vacation->info()
			));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occured and the content couldn\'t be saved: :error', array(':error' => $e->getMessage())));
		}
	}
	
	public function action_delete()
	{
		$vacation = ORM::factory('Vacation',arr::get($_POST, 'id',''));
		if(!$vacation->loaded())
		{
			ajax::error('Ferien blev ikke fundet. Er den allerede blevet slettet?');
		}
		try
		{
			$vacation->delete();
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error('Der opstod en fejl: '.$e->getMessage());
		}
	}
	
}
