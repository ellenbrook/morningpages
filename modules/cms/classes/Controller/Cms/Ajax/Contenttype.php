<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Contenttype extends Controller {
	
	public function action_get()
	{
		$contenttype = ORM::factory('Contenttype', arr::get($_GET, 'id', ''));
		if(!$contenttype->loaded())
		{
			ajax::error(__('Indholdstypen blev ikke fundet. Er den blevet slettet i mellemtiden?'));
		}
		$items = array();
		$contents = $contenttype->contents->find_all();
		if((bool)$contents->count())
		{
			foreach($contents as $content)
			{
				$items[] = array(
					'id' => $content->id,
					'title' => $content->title()
				);
			}
		}
		ajax::success('',array(
			'id' => $contenttype->id,
			'type' => $contenttype->type,
			'slug' => $contenttype->slug,
			'icon' => $contenttype->icon,
			'hierarchical' => $contenttype->hierarchical,
			'has_categories' => $contenttype->has_categories,
			'has_tags' => $contenttype->has_tags,
			'has_timestamp' => $contenttype->has_timestamp,
			'has_thumbnail' => $contenttype->has_thumbnail,
			'has_author' => $contenttype->has_author,
			'display' => $contenttype->display,
			'items' => $items
		));
	}
	
}


