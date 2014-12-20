<?php defined('SYSPATH') or die('No direct script access.');

class Model_Stuffwithslugs extends ORM {
	
	public function link($data = array())
	{
		return '<a href="'.arr::get($data, 'href', $this->url()).'" title="'.arr::get($data, 'title', $this->title()).'">'.arr::get($data, 'content', $this->title()).'</a>';
	}
	
	public function get_active()
	{
		$results = $this->where('status','=','active')->find_all();
		if((bool)$results->count())
		{
			return $results;
		}
		return false;
	}
	
	public function url()
	{
		
	}
	
	public function hit()
	{
		if(true||!user::is_logged('admin'))
		{
			$this->hits += 1;
			$this->save();
		}
	}
	
	protected function checkslug()
	{
		if(!$this->loaded()) // New item. Slugify the slug/title
		{
			$slug = $this->slug;
			if(empty($slug))
			{
				$slug = $this->title;
			}
			$slug = site::slugify($slug);
			$orgslug = $slug;
			$exists = $this->where('slug','=',$slug)->count_all();
			$i=2;
			while((bool)$exists)
			{
				$secondtolast = substr($orgslug,strlen($orgslug)-2,1);
				$last = substr($orgslug,strlen($orgslug)-1,1);
				if($secondtolast == '-' && is_numeric($last))
				{
					$slug = substr($orgslug,0,strlen($orgslug)-1).($last+1);
				}
				else
				{
					$slug = $orgslug.'-'.$i;
				}
				$exists = $this->where('slug','=',$slug)->count_all();
				$i++;
			}
			$this->slug = $slug;
		}
		else
		{
			if($this->changed('slug'))
			{
				$slug = $this->slug;
				$slug = site::slugify($slug);
				$orgslug = $slug;
				$exists = $this->where('slug','=',$slug)->where('id','!=',$this->id)->count_all();
				$i=2;
				while((bool)$exists)
				{
					$secondtolast = substr($orgslug,strlen($orgslug)-2,1);
					$last = substr($orgslug,strlen($orgslug)-1,1);
					if($secondtolast == '-' && is_numeric($last))
					{
						$slug = substr($orgslug,0,strlen($orgslug)-1).($last+1);
					}
					else
					{
						$slug = $orgslug.'-'.$i;
					}
					$exists = $this->where('slug','=',$slug)->where('id','!=',$this->id)->count_all();
					$i++;
				}
				$this->slug = $slug;
			}
		}
	}
	
	public function pagetitle()
	{
		if(!empty($this->pagetitle))
		{
			return $this->pagetitle;
		}
		return $this->title;
	}
	
	public function status()
	{
		switch($this->status)
		{
			case 'active':
				return '<span class="label label-success">Udgivet</span>';
				break;
			case 'draft':
				return '<span class="label label-warning">Kladde</span>';
				break;
			case 'private':
				return '<span class="label label-default">Privat</span>';
				break;
			case 'password':
				return '<span class="label label-default">Passwordbeskyttet</span>';
				break;
		}
	}
	
	public function title()
	{
		if(!empty($this->title))
		{
			return ucfirst($this->title);
		}
		return __('(no title)');
	}
	
	public function set_guid()
	{
		if((bool)$this->parent)
		{
			$parent = ORM::factory('Category')->where('parent','=',$this->parent)->find();
			$this->guid = $parent->guid.'/'.$this->slug;
		}
		else
		{
			$this->guid = $this->slug;
		}
	}
	
}