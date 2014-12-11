<?php defined('SYSPATH') or die('No direct script access.'); 

/**
 * Various site functions.
 */

abstract class routes {
	
	/**
	 * Site routes
	 */
	public static function find($route, $params, $request)
	{
		$controller = (isset($params['controller']) ? strtolower($params['controller']) : 'site');
		$controller = strtolower($controller);
		$controllerfile = ucfirst($controller);
		$action = (isset($params['action']) ? $params['action'] : 'index');
		//$action = ucfirst($action);
		$slug = (isset($params['slug']) ? $params['slug'] : '');
		$slug2 = (isset($params['slug2']) ? $params['slug2'] : '');
		$slug3 = (isset($params['slug3']) ? $params['slug3'] : '');
		$slug4 = (isset($params['slug4']) ? $params['slug4'] : '');
		$slug5 = (isset($params['slug5']) ? $params['slug5'] : '');
		
		// Page alias
		if($controller == 'contact')
		{
			return array(
				'controller' => 'Page',
				'action' => 'contact'
			);
		}
		if($controller == 'faq')
		{
			return array(
				'controller' => 'Page',
				'action' => 'faq'
			);
		}
		if($controller == 'about')
		{
			return array(
				'controller' => 'Page',
				'action' => 'about'
			);
		}
		if($controller == 'terms-conditions')
		{
			return array(
				'controller' => 'Page',
				'action' => 'terms'
			);
		}
		if($controller == 'privacy-policy')
		{
			return array(
				'controller' => 'Page',
				'action' => 'privacy'
			);
		}
		if($controller == 'about')
		{
			return array(
				'controller' => 'Page',
				'action' => 'about'
			);
		}
		if($controller == 'write')
		{
			if(empty($action) || $action == 'index')
			{
				$action = site::today_slug();
			}
			$page = false;
			if(user::logged())
			{
				$page = ORM::factory('Page')
					->where('user_id','=',user::get()->id)
					->where('type','=','page')
					->where('day','=',$action)
					->find();
				if(!$page->loaded() && $action == site::today_slug())
				{
				    
				    $page = ORM::factory('Page')
                        ->where('user_id','=',user::get()->id)
                        ->where('type','=','autosave')
                        ->where('day','=',$action)
                        ->find();
					// It's today, but todays page doesn't exist yet. Create it
					if(!$page->loaded())
                    {
                        $page->type = 'autosave';
                        $page->save();
                    }
				}
			}
			if((user::logged() && $page->loaded()) || !user::logged())
			{
				return array(
					'controller' => 'Page',
					'action' => 'write',
					'page' => $page,
					'daystamp' => $action
				);
			}
			else
			{
				return array(
					'controller' => 'Page',
					'action' => 'daynotfound'
				);
			}
		}
		/*if($controller == 'options')
		{
			return array(
				'controller' => 'Me',
				'action' => 'options'
			);
		}*/
		if($controller == 'read')
		{
			return array(
				'controller' => 'Page',
				'action' => 'read',
				'id' => $action
			);
		}
		
		// Messageboard
		if($controller == 'talk')
		{
			if(empty($action) || $action == 'index')
			{
				return array(
					'controller' => 'Talk',
					'action' => 'index'
				);
			}
			if($action == 'feed')
			{
				return array(
					'controller' => 'Rss',
					'action' => 'talk'
				);
			}
			$talktag = ORM::factory('Talktag')
				->where('slug','=',$action)
				->find();
			if($talktag->loaded())
			{
				if(!empty($slug))
				{
					$talk = ORM::factory('Talk',(int)$slug);
					if($talk->loaded())
					{
						return array(
							'controller' => 'Talk',
							'action' => 'talk',
							'tag' => $talktag,
							'talk' => $talk
						);
					}
					else
					{
						notes::error('We couldnt find that discussion. Sorry!');
						// This is stupid, but Kohana throws an unavoidable exception if I do the redirect from here
						return array(
							'controller' => 'Talk',
							'action' => 'talknotfound',
							'tag' => $talktag
						);
					}
				}
				return array(
					'controller' => 'Talk',
					'action' => 'index',
					'tag' => $talktag
				);
			}
			else
			{
				// This is stupid, but Kohana throws an unavoidable exception if I do the redirect from here
				return array(
					'controller' => 'Talk',
					'action' => 'tagnotfound'
				);
			}
		}
		
		if($controller == 'user')
		{
			if($action != '')
			{
				if(in_array($action, user::reservednames()))
				{
					return array(
						'controller' => 'User',
						'action' => $action
					);
				}
				// We're either looking at a user's public profile or 404'd
				$user = ORM::factory('User')->where('slug','=',$action)->find();
				if($user->loaded())
				{
					if((bool)$user->option('public'))
					{
						return array(
							'controller' => 'Me',
							'action' => 'profile',
							'user' => $user
						);
					}
					else
					{
						return array(
							'controller' => 'Me',
							'action' => 'notpublic'
						);
					}
				}
				else
				{
					return array(
						'controller' => 'Errors',
						'action' => '404',
						'params' => $params
					);
				}
			}
			else
			{
				return array(
					'controller' => 'User',
					'action' => 'options'
				);
			}
		}
		
		// "Static" controllers
		$file = 'application/classes/Controller/' . $controllerfile . '.php';
		if(file_exists($file) && method_exists('Controller_'.ucfirst($controllerfile), 'action_'.$action))
		{
			$return = array();
			$return['controller'] = $controllerfile;
			$return['action'] = ((isset($action))?$action:'index');
			$return['id'] = ((isset($slug))?$slug:'');
			$return['params'] = $params;
			return $return;
		}
		
		// No matches. 404
		return array(
			'controller' => 'Errors',
			'action' => '404',
			'params' => $params
		);
	}
	
	public static function isFrontPage()
	{
		$controller = Request::current()->controller();
		$action = Request::current()->action();
		return $controller == 'Site' && $action == 'index';
	}
	
}
