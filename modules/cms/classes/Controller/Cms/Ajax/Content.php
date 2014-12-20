<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Content extends Controller {
	
	public function action_delete()
	{
		if(!user::logged('admin'))
		{
			ajax::error(__('You must be logged in to delete content'));
		}
		if($_POST)
		{
			$delete = arr::get($_POST, 'delete', false);
			if($delete)
			{ 
				try
				{
					if(is_array($delete))
					{
						foreach($delete as $id)
						{
							$content = ORM::factory('Content', $id);
							if($content->loaded())
							{
								$content->delete();
							}
						}
					}
					else
					{
						$content = ORM::factory('Content', $delete);
						if($content->loaded())
						{
							$content->delete();
						}
					}
					ajax::success(__('The content has been deleted'));
				}
				catch(exception $e)
				{
					ajax::error(__('An error occurred and the content couldn\'t be deleted: :errormessage',array(':errormessage' => $e->getMessage())));
				}
			}
			ajax::error(__('No data recieved'));
		}
	}
	
	public function action_copy()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'id'));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it already been deleted?'));
		}
		
		try
		{
			$new = $content->copy();
			ajax::success(__('Copy created'),array(
				'id' => $new->id
			));
		}
		catch(exception $e)
		{
			ajax::error($e->getMessage().' '.$e->getFile().' '.$e->getLine());
		}
	}
	
	public function action_setthumb()
	{
		if($_POST)
		{
			$content = ORM::factory('Content', arr::get($_POST, 'content', false));
			if(!$content->loaded())
			{
				ajax::error(__('The content wasn\'t found. Has it already been deleted?'));
			}
			$file = ORM::factory('File', arr::get($_POST, 'file',false));
			if(!$file->loaded())
			{
				ajax::error(__());
			}
			try
			{
				$content->image_id = $file->id;
				$content->save();
				ajax::success('',array(
					'thumb' => $file->show(150, null, Image::WIDTH)
				));
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the thumbnail couldn\'t be saved: :error', array(':error' => $e->getMessage())));
			}
		}
		ajax::error(__('No data recieved'));
	}
	
	public function action_clearthumb()
	{
		if($_POST)
		{
			$content = ORM::factory('Content', arr::get($_POST, 'content', false));
			if(!$content->loaded())
			{
				ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
			}
			try
			{
				$content->image_id = 0;
				$content->save();
				ajax::success();
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the thumbnail couldn\'t be saved: :error', array(':error' => $e->getMessage())));
			}
		}
		ajax::error(__('No data recieved'));
	}
	
	public function action_edit()
	{
		$content = ORM::factory('Content',$this->request->param('id'));
		
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
		}
		if($_POST)
		{
			$oldparent = $content->parent;
			
			$content->values($_POST);
			
			if($content->contenttype->supports('thumbnail'))
			{
				$content->image_id = arr::get($_POST, 'thumbnail', '0');
			}
			
			/**
			 * Publish date
			 */
			if($content->contenttype->supports('timestamp'))
			{
				$publish = arr::get($_POST, 'publishdate', false);
				if($publish && !empty($publish))
				{
					$date = $publish;
					if(empty($date)) $date = date('d-m-Y');
					list($day,$month,$year) = explode('-',$date);
					$content->published = mktime(0,0,0,$month,$day,$year);
				}
			}
			
			/**
			 * Categories
			 */
			if($content->contenttype->supports('categories'))
			{
				$content->remove('categories');
				if(arr::get($_POST,'category',false))
				{
					foreach(arr::get($_POST, 'category') as $id)
					{
						$content->add('categories',$id);
					}
				}
			}
			
			/**
			 * Status
			 */
			if(arr::get($_POST, 'publishbtn', false))
			{
				$content->status = 'active';
			}
			elseif(arr::get($_POST, 'draftbtn',false))
			{
				$content->status = 'draft';
			}
			
			try
			{
				$content->check_slug_and_guid();
				$content->save();
				/**
				 * Content blocks
				 */
				if(arr::get($_POST, 'block', false))
				{
					foreach(arr::get($_POST, 'block') as $id => $value)
					{
						$block = ORM::factory('Block',$id);
						if($block->loaded())
						{
							if(is_array($value))
							{
								$value = json_encode($value);
							}
							$block->value = $value;
							
							// Currently done in real time. Meaning order is saved before hitting the save button. Might want to change it.
							/*if(arr::get($_POST, 'blockorder', false) && arr::get($_POST['blockorder'], $block->id, false))
							{
								$block->order = arr::get($_POST['blockorder'], $block->id);
							}*/
							
							$block->save();
							
							$block->remove_all_files();
							$value = json_decode($value);
							
							if(isset($value->files) && $value->files)
							{
								foreach($value->files as $f)
								{
									$file = ORM::factory('Block_File');
									$file->block_id = $block->id;
									$file->file_id = $f->id;
									$file->description = $f->description;
									$file->alt =  $f->alt;
									$file->save();
								}
							}
							
						}
					}
				}
				
				/**
				 * Files
				 */
				 /*if(arr::get($_POST, 'file', false))
				 {
				 	$descriptions = arr::get($_POST, 'filedescription', array());
					$alts = arr::get($_POST, 'alt', array());
					try
					{
					 	foreach(arr::get($_POST, 'file') as $blockid => $fileid)
						{
							$fileid = key($fileid);
							$block = ORM::factory('Block', $blockid);
							$block->remove_all_files();
							$description = arr::get($descriptions, $blockid, array());
							$alt = arr::get($alts, $blockid, array());
							
							$file = ORM::factory('Block_File');
							$file->block_id = $blockid;
							$file->file_id = $fileid;
							$file->description = arr::get($description, $fileid, '');
							$file->alt =  arr::get($alt, $fileid, '');
							$file->save();
						}
					}
					catch(exception $e)
					{
						ajax::error('Der opstod en fejl: '.$e->getMessage());
					}
				 }*/
				ajax::success(__('Saved'));
				//cms::redirect('content/edit/'.$content->id);
			}
			catch(HTTP_Exception_Redirect $e)
			{
				throw $e;
			}
			catch(exception $e)
			{
				ajax::error(__('An uncaught error occured and the content couldn\'t be saved: :error', array(':error', $e->getMessage())));
			}
		}
	}
	
	public function action_info()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'id', false));
		if(!$content->loaded())
		{
			ajax::error(__('Indholdet blev ikke fundet, er det blevet slettet?'));
		}
		
		$info = $content->info();
		$info['blocks'] = $content->blocks();
		$info['tags'] = $content->tags();
		$info['blocktypes'] = $content->blocktypes();
		
		ajax::success('ok', $info);
	}
	
	public function action_quicksave()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'id', false));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
		}
		
		$content->status = arr::get($_POST, 'status','draft');
		$content->title = arr::get($_POST, 'title',$content->title);
		try
		{
			$content->save();
			ajax::success('Gemt', array(
				'info' => $content->info()
			));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occured and the content couldn\'t be saved: :error', array(':error' => $e->getMessage())));
		}
	}
	
	public function action_addtag()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'id', false));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
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
		$content->add('tags',$tag);
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
		$content = ORM::factory('Content', arr::get($_POST, 'id', false));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
		}
		$tag = ORM::factory('Tag', arr::get($_POST, 'tag', false));
		if(!$tag->loaded())
		{
			ajax::error(__('The tag wasn\'t found. Has it been deleted in the meantime?'));
		}
		$content->remove('tags', $tag);
		ajax::success();
	}
	
	public function action_getblocks()
	{
		$content = ORM::factory('Content', arr::get($_POST, 'id', false));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
		}
		$blocks = $content->blocks
			->where('parent','=',arr::get($_POST, 'parent', '0'))
			->find_all();
		$json = array();
		if($blocks->count())
		{
			foreach($blocks as $block)
			{
				$json[] = $block->info();
			}
		}
		ajax::success('ok', array('blocks' => $json));
	}
	
}


