<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rss extends Controller {
	
	public function action_talk()
	{
		$this->response->headers('Content-Type', 'application/rss+xml; charset=UTF-8');
		
		$talks = ORM::factory('Talk')
			->where('deleted','=',0)
			->limit(15)
			->order_by('created', 'DESC')
			->find_all();
		
		$feed = array();
		if((bool)$talks->count())
		{
			foreach($talks as $talk)
			{
				$description = $talk->content();
				$description = strip_tags($description);
				$description = str_replace(array("\r\n","\n"), ' ', $description);
				$description = substr($description, 0, 200);
				$feed[] = array(
					'title' => $talk->title,
					'pubDate' => $talk->created,
					'link' => $talk->url(),
					'description' => $description,
					'guid' => $talk->url()
				);
			}
		}
		
		echo Feed::create(array(
			'title' => 'Talk about Morning Pages',
			'link' => 'talk',
			'description' => 'New talks on Morning Pages'
		), $feed);
	}
	
}
