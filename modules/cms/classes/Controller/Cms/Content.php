<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Content extends Controller {
	
	private function check_contenttype()
	{
		$contenttype = ORM::factory('Contenttype',$this->request->param('id'));
		if(!$contenttype->loaded())
		{
			notes::add('error', 'Contenttype not found.');
			cms::redirect();
		}
		return $contenttype;
	}
	
	private function loop_content_rows($contents, $level = 0)
	{
		$cont = array();
		foreach($contents as $content)
		{
			$info = $content->baseinfo();
			$info['url'] = $content->url();
			$info['level'] = $level;
			$info['children'] = $this->loop_content_rows($content->children(), $level+1);
			$cont[] = $info;
		}
		return $cont;
	}
	
	public function action_index()
	{
		$contenttype = $this->check_contenttype();
		
		$contents = $contenttype->contents;
		$counter = $contenttype->contents;
		if((bool)$contenttype->supports('hierarchy'))
		{
			$contents = $contents->where('parent','=',0);
			$counter = $counter->where('parent','=',0);
		}
		if($contenttype->supports('timestamp'))
		{
			$contents = $contents->order_by('created', 'DESC');
		}
		$contents = $contents->where('splittest','=',0);
		
		$numresults = $counter->count_all();
		$limit = 20;
		$offset = cms::offset($numresults, $limit);
		
		$contents = $contents
			->limit($limit)
			->offset($offset);
		$contents = $this->loop_content_rows($contents->find_all());
		
		$pagination = cms::pagination($numresults, '#/content/index/'.$contenttype->id, $limit);
		$view = View::factory('Cms/Content/index2');
		$view->contenttype = $contenttype;
		$view->pagination = $pagination;
		
		$allcontent = DB::select('id','title')
			->from('contents')
			->where('contenttype_id','=',$contenttype->id)
			->execute()
			->as_array();
		
		reply::ok($view, 'contenttype-'.$contenttype->id, array(
			'viewModel' => 'viewModels/Content/index',
			'contents' => $contents,
			'contenttype' => $contenttype->baseinfo(),
			'allcontent' => $allcontent
		));
	}
	
	public function action_new()
	{
		$contenttype = $this->check_contenttype();
		$content = ORM::factory('Content');
		$content->user_id = user::get()->id;
		$content->contenttype_id = $contenttype->id;
		$typeid = $this->request->param('typeid');
		$content->contenttypetype_id = (isset($typeid)&&!empty($typeid)?$typeid:'0');
		$content->title = '';
		$content->status = 'draft';
		$content->created = time();
		try
		{
			$content->save();
			$blocks = $contenttype->blocktypes
				->where('min','>',0)
				->where('parent','=',0)
				->where('contenttypetype_id','=',$content->contenttypetype_id)
				->find_all();
			if((bool)$blocks->count())
			{
				$loop = 0;
				foreach($blocks as $block)
				{
					for($i = 0;$i<$block->min;$i++)
					{
						$contentblock = ORM::factory('Block');
						$contentblock->content_id = $content->id;
						$contentblock->blocktype_id = $block->id;
						$contentblock->order = $loop;
						$contentblock->save();
						$loop++;
					}
				}
			}
			//cms::redirect('content/edit/'.$content->id);
			ajax::success('ok', array('id' => $content->id));
		}
		catch(HTTP_Exception_Redirect $e)
		{
			throw $e;
		}
		catch(exception $e)
		{
			notes::add('error','Der opstod en fejl: '.$e->getMessage());
			echo 'error';
			//cms::redirect('content/index/'.$contenttype->id);
		}
	}
	
	public function action_delete()
	{
		$content = ORM::factory('Content',$this->request->param('id'));
		if(!$content->loaded())
		{
			notes::error('Indholdet blev ikke fundet. Er det allerede blevet slettet?');
			cms::redirect('');
		}
		try
		{
			$type = $content->contenttype_id;
			$content->delete();
			cms::redirect('content/index/'.$type);
		}
		catch(HTTP_Exception_Redirect $e)
		{
			throw $e;
		}
		catch(exception $e)
		{
			notes::error('Der opstod en fejl og indholdet kunne ikke slettes: '.$e->getMessage());
			cms::redirect('content/index/'.$content->id);
		}
	}
	
	public function action_edit()
	{
		$content = ORM::factory('Content',$this->request->param('id'));
		
		if(!$content->loaded())
		{
			notes::add('error','Indholdet blev ikke fundet.');
			//cms::redirect();
		}
		$view = View::factory('Cms/Content/edit', array(
			'content' => $content
		));
		
		$tags = DB::select('tag_id')
			->from('contents_tags')
			->group_by('tag_id')
			->execute();
		$alltags = array();
		if((bool)$tags->count()) foreach($tags as $tag)
		{
			$tag = ORM::factory('Tag', arr::get($tag, 'tag_id'));
			$alltags[] = array(
				'title' => $tag->tag,
				'id' => $tag->id,
				'slug' => $tag->slug
			);
		}
		
		reply::ok($view, 'contenttype-'.$content->contenttype_id, array(
			'viewModel' => 'viewModels/Content/edit',
			'alltags' => $alltags
		));
		
	}
	
}
