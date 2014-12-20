<?php defined('SYSPATH') or die('No direct script access.');
class Model_Blocktype extends ORM {
	
	protected $_belongs_to = array(
		'contenttype' => array(),		'contenttypetype' => array()
	);
	
	protected $_has_many = array(
		'blocks' => array(),
		'metas' => array('model' => 'Blocktype_Meta'),		'blocktypes' => array('foreign_key' => 'parent')
	);		protected $_sorting = array(		'order' => 'ASC'	);
		public function copy($newcontenttypetype_id = false, $newparent = false)	{		$new = ORM::factory('Blocktype');		$new->contenttype_id = $this->contenttype_id;		if($newcontenttypetype_id)		{			$new->contenttypetype_id = $newcontenttypetype_id;		}		else		{			$new->contenttypetype_id = $this->contenttypetype_id;		}		if(!$newparent)		{			$new->parent = $this->parent;		}		else		{
			$new->parent = $newparent;
		}		$new->type = $this->type;		$new->key = $this->key;		$new->display = $this->display;		$new->min = $this->min;		$new->max = $this->max;		if($new->parent == 0)		{			$parent = ORM::factory('Blocktype', $new->parent)->find();			$new->order = $parent->blocktypes->count_all();		}		else		{			$new->order = $new->contenttypetype->blocktypes->count_all();		}		$new->save();		$kids = $this->blocktypes->find_all();		if((bool)$kids->count())		{			foreach($kids as $kid)			{				$kid->copy($newcontenttypetype_id, $new->id);			}		}		$metas = $this->metas->find_all();		if((bool)$metas->count())		{			foreach($metas as $meta)			{				$meta->copy($new->id);			}		}		return $new;	}	
	public function meta($key)
	{
		$meta = $this->metas->where('key','=',$key)->find();
		if(!$meta->loaded())
		{
			return false;
		}
		return $meta->value;
	}		public function info()	{		$children = array();		if((bool)$this->blocktypes->count_all())		{			foreach($this->blocktypes->find_all() as $blocktype)			{				$children[] = $blocktype->info();			}		}				$metas = array();		if((bool)$this->metas->count_all())		{			foreach($this->metas->find_all() as $meta)			{				$metas[] = $meta->info();			}		}				$contents = array();		if($this->type == 'contentselecter')		{			$contenttypeid = $this->meta('contenttype');			$zecontents = ORM::factory('Content');			if($contenttypeid != 0)			{				$zecontents = $zecontents->where('contenttype_id','=',$contenttypeid);			}			$zecontents = $zecontents->find_all();			if((bool)$zecontents->count())			{				foreach($zecontents as $content)				{					$contents[] = array(						'id' => $content->id,						'title' => $content->title					);				}			}		}				return array(			'id' => $this->id,			'contenttype_id' => $this->contenttype_id,			'contenttypetype_id' => $this->contenttypetype_id,			'parent' => $this->parent,			'type' => $this->type,			'key' => $this->key,			'display' => $this->display,			'min' => $this->min,			'max' => $this->max,			'order' => $this->order,			'children' => $children,			'meta' => $metas,			'contents' => $contents		);	}
		public function delete()	{		$blocks = $this->blocks->find_all();		if((bool)$blocks->count())		{			foreach($blocks as $block)			{				$block->delete();			}		}		$metas = $this->metas->find_all();		if((bool)$metas->count())		{			foreach($metas as $meta)			{				$meta->delete();			}		}		return parent::delete();	}	
}