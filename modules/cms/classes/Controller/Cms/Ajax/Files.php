<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Files extends Controller {
	
	public function action_get_filebrowser_files()
	{
		$limit = arr::get($_GET, 'limit', 10);
		$offset = arr::get($_GET, 'offset', 0);
		$files = ORM::factory('File')
			->order_by('created','DESC')
			->limit($limit)
			->offset($offset)
			->find_all();
		try
		{
			$json = array();
			if((bool)$files->count())
			{
				foreach($files as $file)
				{
					$json[] = $file->info();
				}
			}
			ajax::success('ok', array('files' => $json));
		}
		catch(exception $e)
		{
			ajax::error('An uncaught error uccurred and the files coundn\'t be fetched: :error', array(':error' => $e->getMessage()));
		}
	}
	
	public function action_addtag()
	{
		$file = ORM::factory('File', arr::get($_POST, 'id', false));
		if(!$file->loaded())
		{
			ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
		}
		$tagtext = arr::get($_POST, 'tag', false);
		if(!$tagtext)
		{
			ajax::error(__('No tag recieved'));
		}
		$slug = site::slugify($tagtext);
		$tag = ORM::factory('Tag')
			->where('slug','=',$slug)
			->find();
		if(!$tag->loaded())
		{
			$tag->tag = $tagtext;
			$tag->slug = $slug;
			$tag->save();
		}
		$file->add('tags',$tag);
		ajax::success('ok', array(
			'tag' => array(
				'id' => $tag->id,
				'tag' => $tag->tag,
				'slug' => $tag->slug
			) 
		));
	}
	
	public function action_removetag()
	{
		$file = ORM::factory('File', arr::get($_POST, 'id', false));
		if(!$file->loaded())
		{
			ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
		}
		$tag = ORM::factory('Tag', arr::get($_POST, 'tag', false));
		if(!$tag->loaded())
		{
			ajax::error(__('The tag wasn\'t found. Has it been deleted?'));
		}
		$file->remove('tags', $tag);
		ajax::success();
	}
	
	public function action_get()
	{
		$limit = arr::get($_POST, 'limit', 30);
		$page = arr::get($_POST, 'page', 1);
		$page = $page-1;
		$files = ORM::factory('File')
			->limit($limit)
			->offset($page*$limit)
			->find_all();
		$json = array();
		if((bool)$files->count())
		{
			foreach($files as $file)
			{
				$json[] = $file->info();
			}
		}
		$total = ORM::factory('File')->count_all();
		ajax::success('', array(
			'files' => $json,
			'total' => ceil($total/$limit)
		));
	}
	
	public function action_save_file_alt()
	{
		$file = ORM::factory('File', arr::get($_POST, 'id',''));
		if($file->loaded())
		{
			$file->alt = arr::get($_POST, 'alt', '');
			try
			{
				$file->save();
				ajax::success('ok');
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the info couldn\'t be saved: :error', array(':error' => $e->getMessage())));
			}
		}
		ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
	}
	
	public function action_save_file_description()
	{
		$file = ORM::factory('File', arr::get($_POST, 'id',''));
		if($file->loaded())
		{
			$file->description = arr::get($_POST, 'description', '');
			try
			{
				$file->save();
				ajax::success('ok');
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the info couldn\'t be saved: :error', array(':error' => $e->getMessage())));
			}
		}
		ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
	}
	
	public function action_save_file_title()
	{
		$file = ORM::factory('File', arr::get($_POST, 'id',''));
		if($file->loaded())
		{
			$file->title = arr::get($_POST, 'title', '');
			try
			{
				$file->save();
				ajax::success('ok');
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the info couldn\'t be saved: :error', array(':error' => $e->getMessage())));
			}
		}
		ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
	}
	
	public function action_delete()
	{
		if($_POST)
		{
			try
			{
				$file = ORM::factory('File', arr::get($_POST, 'id', false));
				if($file->loaded())
				{
					$file->delete();
				}
				else
				{
					ajax::error(__('The file wasn\'t found. Has it been deleted in the meantime?'));
				}
				ajax::success('ok');
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occurred and the file couldn\'t be deleted: :error', array(':error' => $e->getMessage())));
			}
		}
	}
	
	public function action_filebrowser_upload()
	{
		try {
			$file = ORM::factory('File')->upload($_FILES['files']);
			ajax::success('OK', array('file' => $file->info()));
		}
		catch(exception $e)
		{
			ajax::error(__('An error occurred and the file :filename couldn\'t be uploaded: :error', array(
				':error' =>$e->getMessage(),
				':filename' => $file['name'][0]
			)));
		}
	}
	
}
