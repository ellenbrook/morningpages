<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Write extends Controller {
	
	public function action_autosave()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in');
		}
		
        $user = user::get();
		$autosave = ORM::factory('Page')
            ->where('user_id','=', $user->id)
            ->where('type','=','autosave')
            ->find();
		if(!$autosave->loaded())
		{
			$autosave->user_id = $user->id;
            $autosave->type = 'autosave';
		}
		
		$content = arr::get($_POST, 'content','');
		$autosave->content = $content;
		
		try
		{
			$autosave->save();
			ajax::success('Page saved!');
		}
		catch(ORM_Validation_Exception $e)
		{
			ajax::error('Validation error');
			$errors = $e->errors('models');
		}
		
		ajax::info('Nothing to do');
	}
	
	public function action_rid()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in to see this.', array(
				'problem' => 'auth'
			));
		}
		$user = user::get();
		$langs = array(
			1 => 'english',
			17 => 'french',
			19 => 'german',
			22 => 'hungarian',
			34 => 'portugese'
		);
		$userlang = $user->option('language');
		if(!key_exists($userlang, $langs))
		{
			ajax::error('We currently only have RID data available for English, French, German, Hungarian and Portugese.', array(
				'problem' => 'data'
			));
		}
		$page = ORM::factory('Page', arr::get($_POST, 'id', ''));
		if(!$page->loaded() || !$page->user_id == $user->id)
		{
			ajax::error('That page wasn\'t found!');
		}
		
		if($page->rid == '')
		{
			require(Kohana::find_file('vendor/rid', 'rid'));
			$rid = new RID();
			$rid->load_dictionary(Kohana::find_file('vendor/rid', $langs[$userlang], 'cat'));
			$data = $rid->analyze($page->content());
			
			$vals = array();
			$colors = array(
				'#B0BF1A','#7CB9E8','#C9FFE5','#B284BE','#5D8AA8','#00308F','#00308F','#AF002A','#F0F8FF','#E32636',
				'#C46210','#EFDECD','#E52B50','#AB274F','#F19CBB','#3B7A57','#FFBF00','#FF7E00','#FF033E','#9966CC',
				'#A4C639','#CD9575','#665D1E','#915C83','#841B2D','#FAEBD7','#008000','#8DB600','#FBCEB1','#00FFFF',
				'#7FFFD4','#4B5320','#3B444B','#8F9779','#E9D66B','#B2BEB5','#87A96B','#FF9966','#A52A2A','#FDEE00',
				'#6E7F80','#568203','#007FFF'
			);
			$i=0;
			foreach($data->category_percentage as $key => $val)
			{
				$vals[] = array(
					'value' => $val,
					'label' => $key,
					'color' => $colors[$i]
				);
				$i++;
			}
			
			$page->rid = serialize($vals);
			$page->save();
		}
		
		ajax::success('ok', array(
			'data' => unserialize($page->rid)
		));
	}
	
	public function action_getautosave()
	{
		if(!user::logged())
		{
			ajax::error('You must be logged in');
		}
		$user = user::get();
		$autosave = ORM::factory('Page')
            ->where('user_id','=', $user->id)
            ->where('type','=','autosave')
            ->find();
		$content = '';
		if($autosave->loaded() && $autosave->content != '')
		{
			$content = $autosave->decode($autosave->content);
		}
		ajax::success('',array(
			'content' => $content,
			'md5' => md5($content)
		));
	}
	
}
	