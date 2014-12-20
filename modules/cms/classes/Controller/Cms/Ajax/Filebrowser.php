<?php

class Controller_Cms_Ajax_Filebrowser extends Controller {
	
	public function action_delete()
	{
		if($_POST)
		{
			$id = arr::get($_POST, 'id', false);
			if(!$id)
			{
				ajax:error(__('No data recieved'));
			}
			try
			{
				if(is_array($id))
				{
					foreach($id as $fid)
					{
						ORM::factory('File', $fid)->delete();
					}
				}
				else
				{
					ORM::factory('File', arr::get($_POST, 'id', false))->delete();
				}
				ajax::success('ok');
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occurred and the file couldn\'t be deleted: :error',array(':error' => $e->getMessage())));
			}
		}
	}
	
	public function action_info()
	{
		// Tags
		$tags = array();
		$tmp = DB::select('files_tags.file_id', 'files_tags.tag_id', 'tags.tag', 'tags.slug')
			->from('files_tags')
			->join('tags')
			->on('files_tags.tag_id', '=','tags.id')
			->group_by('files_tags.tag_id')
			->execute();
		if((bool)$tmp->count())
		{
			foreach($tmp as $tag)
			{
				$tags[] = array(
					'id' => arr::get($tag, 'tag_id'),
					'tag' => arr::get($tag, 'tag'),
					'slug' => arr::get($tag, 'slug')
				);
			}
		}
		ajax::success('ok', array(
			'tags' => $tags
		));
	}
	
	public function action_files()
	{
		$files = array();
		$limit = arr::get($_GET, 'limit', 10);
		$offset = arr::get($_GET, 'offset', 0);
		
		$query = ORM::factory('File');
		
		if(arr::get($_GET, 'tags', false))
		{
			$tags = arr::get($_GET, 'tags');
			if(arr::get($_GET, 'matchAll', false) == "true")
			{
				$tagsquery = DB::select('files_tags.file_id')
					->from('files_tags')
					->where('files_tags.tag_id', 'IN', $tags)
					->group_by('files_tags.file_id')
					->having(DB::expr('COUNT(files_tags.file_id)'),'=',count($tags))
					->execute();
					//die(var_dump($tagsquery));
			}
			else
			{
				$tagsquery = DB::select('files_tags.file_id')
					->distinct(true)
					->from('files_tags')
					->where('files_tags.tag_id', 'IN', $tags)
					//->join('tags')
					//->on('tags.id','=','files_tags.tag_id')
					->execute();
					//die(var_dump($tagsquery));
			}
			if((bool)$tagsquery->count())
			{
				$ids = array();
				foreach($tagsquery as $q)
				{
					$ids[] = arr::get($q, 'file_id');
					//$files[] = ORM::factory('File', arr::get($q, 'file_id'))->info();
				}
				$query = $query->where('id', 'IN', $ids);
			}
			else
			{
				// Empty resultset
				ajax::success('ok', array(
					'files' => array()
				));
			}
		}
		$query = $query
			->order_by('created','DESC')
			->limit($limit)
			->offset($offset)
			->find_all();
		if((bool)$query->count())
		{
			foreach($query as $file)
			{
				$files[] = $file->info();
			}
		}
		
		ajax::success('ok', array(
			'files' => $files
		));
	}
	
}
