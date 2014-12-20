<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Navigation extends Controller {
	
	public function action_get()
	{
		$menu = ORM::factory('Menu', arr::get($_GET, 'id',''));
		if(!$menu->loaded())
		{
			ajax::error(__('The menu wasn\'t found. Has it been deleted in the meantime?'));
		}
		
		ajax::success('ok',array(
			'menu' => $menu->info()
		));
	}
	
	public function action_info()
	{
		$allmenus = ORM::factory('Menu')->find_all();
		$menus = array();
		if((bool)$allmenus->count())
		{
			foreach($allmenus as $menu)
			{
				$menus[] = array(
					'id' => $menu->id,
					'menutype_id' => $menu->menutype_id,
					'title' => $menu->title
				);
			}
		}
		$allmenutypes = ORM::factory('Menutype')->find_all();
		$menutypes = array();
		if((bool)$allmenutypes->count())
		{
			foreach($allmenutypes as $menutype)
			{
				$menutypes[] = array(
					'id' => $menutype->id,
					'type' => $menutype->type,
					'title' => $menutype->title,
					'description' => $menutype->description
				);
			}
		}
		ajax::success('',array(
			'menus' => $menus,
			'menutypes' => $menutypes
		));
	}
	
	public function action_deletemenu()
	{
		$menu = ORM::factory('Menu', arr::get($_POST, 'id'));
		if(!$menu->loaded())
		{
			ajax::error(__('The menu wasn\'t found. Has it been deleted in the meantime?'));
		}
		try
		{
			$menu->delete();
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error('An error occured and the menu couldn\'t be deleted: :error',array(':error' => $e->getMessage()));
		}
	}
	
	public function action_addnewmenu()
	{
		$menu = ORM::factory('Menu');
		$menu->title = arr::get($_POST, 'name','');
		try
		{
			$menu->save();
			ajax::success(__('Menu created'), array(
				'menu' => array(
					'id' => $menu->id,
					'menutype_id' => $menu->menutype_id,
					'title' => $menu->title
				)
			));
		}
		catch(exception $e)
		{
			ajax::error(__('An error occured and the menu couldn\'t be saved: :error', array(':error' => $e->getMessage())));
		}
	}
	
	private function save_menu_item($menuitem, $menu_id, $parent, $order)
	{
		$contentid = arr::get($menuitem, 'content_id',0);
		if($contentid != 0)
		{
			$content = ORM::factory('Content', $contentid);
			$url = $content->guid;
		}
		else
		{
			$url = arr::get($menuitem, 'url','');
		}
		$item = ORM::factory('Menu_Item');
		$item->menu_id = $menu_id;
		$item->content_id = $contentid;
		$item->parent = $parent;
		$item->type = arr::get($menuitem, 'type','');
		$item->url = $url;
		$item->linktext = arr::get($menuitem, 'linktext','');
		$item->titletext = arr::get($menuitem, 'titletext','');
		$item->classes = arr::get($menuitem, 'classes','');
		$item->order = $order;
		$item->save();
		if((bool)arr::get($menuitem, 'kids',array()))
		{
			$i = 0;
			foreach(arr::get($menuitem, 'kids') as $kid)
			{
				$this->save_menu_item($kid, $menu_id, $item->id, $i);
				$i++;
			}
		}
	}
	
	public function action_savemenu()
	{
		$menu = ORM::factory('Menu', arr::get($_POST, 'id'));
		if(!$menu->loaded())
		{
			ajax::error(__('The menu wasn\'t found. Has it been deleted in the meantime?'));
		}
		try
		{
			$menu->menutype_id = arr::get($_POST, 'menutype',0);
			$menu->title = arr::get($_POST, 'title','');
			$menu->save();
			$menuitems = $menu->items->find_all();
			if((bool)$menuitems->count())
			{
			 	foreach($menuitems as $item)
				{
					$item->delete();
				}
			}
			$items = arr::get($_POST, 'items',array());
			if((bool)count($items))
			{
				$i = 0;
				foreach($items as $item)
				{
					$this->save_menu_item($item, $menu->id, 0, $i);
					$i++;
				}
			}
			ajax::success(__('Gemt'));
		}
		catch(exception $e)
		{
			ajax::error('An error occured and the menu couldn\'t be saved: :error', array(':error'=>$e->getMessage()));
		}
	}
	
	public function action_deletemenuitem()
	{
		$item = ORM::factory('Menu_Item', arr::get($_POST, 'id'));
		if(!$item->loaded())
		{
			ajax::error(__('The menuitem wasn\'t found. Has it been deleted in the meantime?'));
		}
		try
		{
			$item->delete();
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error(__('Der opstod en fejl og menupunktet kunne ikke gemmes: :error',array(':error'=>$e->getMessage())));
		}
	}
	
	public function action_addinternal()
	{
		$menu = ORM::factory('Menu', arr::get($_POST, 'id',''));
		if(!$menu->loaded())
		{
			ajax::error(__('The menu wasn\'t found. Has it been deleted in the meantime?'));
		}
		
		$content = ORM::factory('Content', arr::get($_POST, 'content',''));
		if(!$content->loaded())
		{
			ajax::error(__('The content wasn\'t found. Has it been deleted in the meantime?'));
		}
		
		$item = ORM::factory('Menu_Item');
		$item->menu_id = $menu->id;
		$item->content_id = $content->id;
		$item->linktext = $content->title();
		$item->titletext = $content->title();
		$item->order = $menu->items->count_all();
		try
		{
			$item->save();
			ajax::success('',array(
				'item' => $item->info()
			));
		}
		catch(exception $e)
		{
			ajax::error(__('An error occurred and the menuitem couldn\'t be added: :error',array(':error'=>$e->getMessage())));
		}
	}
	
}

