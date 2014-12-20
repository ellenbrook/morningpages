<?php defined('SYSPATH') or die('No direct script access.');

class Model_Contenttypetype extends ORM {
	
	protected $_has_many = array(
		'contents' => array(),
		'blocktypes' => array()
	);
	
	protected $_belongs_to = array(
		'contenttype' => array(),
	);
	
	protected $_sorting = array(
		'order' => 'ASC'
	);
	
	public function copy($newcontenttype_id = false)
	{
		$new = ORM::factory('Contenttypetype');
		if($newcontenttype_id)
		{
			$new->contenttype_id = $newcontenttype_id;
		}
		else
		{
			$new->contenttype_id = $this->contenttype_id;
		}
		$new->key = $this->key;
		$new->display = $this->display.' ('.__('Copy').')';
		$new->min = $this->min;
		$new->max = $this->max;
		$new->order = $this->contenttype->contenttypetypes->count_all();
		$new->save();
		$blocktypes = $this->blocktypes->find_all();
		if((bool)$blocktypes->count())
		{
			foreach($blocktypes as $blocktype)
			{
				$blocktype->copy($new->id);
			}
		}
		return $new;
	}
	
	public function info()
	{
		$blocktypes = array();
		$blocks = $this->blocktypes
			->where('parent','=',0)
			->find_all();
		if((bool)$blocks->count())
		{
			foreach($blocks as $blocktype)
			{
				$blocktypes[] = $blocktype->info();
			}
		}
		return array(
			'id' => $this->id,
			'contenttype_id' => $this->contenttype_id,
			'key' => $this->key,
			'display' => $this->display,
			'min' => $this->min,
			'max' => $this->max,
			'order' => $this->order,
			'blocktypes' => $blocktypes
		);
	}
	
	public function baseinfo()
	{
		return array(
			'id' => $this->id,
			'contenttype_id' => $this->contenttype_id,
			'key' => $this->key,
			'display' => $this->display,
			'min' => $this->min,
			'max' => $this->max,
			'order' => $this->order
		);
	}
	
	public function delete()
	{
		if((bool)$this->contents->count_all())
		{
			foreach($this->contents->find_all() as $content)
			{
				$content->delete();
			}
		}
		return parent::delete();
	}
	
}
