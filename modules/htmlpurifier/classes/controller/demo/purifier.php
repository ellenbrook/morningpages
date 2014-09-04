<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Demo_Purifier extends Controller_Demo {

	public function before()
	{
		parent::before();

		// Get HTML Purifier configuration
		$config = Kohana::config('purifier');

		// Do not finalize, we need to be able to set configuration options!
		$config['finalize'] = FALSE;
	}

	public function demo_clean()
	{
		$this->content = View::factory('demo/purifier/clean')
			->bind('dirty', $dirty)
			->bind('clean', $clean)
			;

		// Get dirty input from GET or POST
		$dirty = Arr::get($_REQUEST, 'dirty');

		if (isset($dirty))
		{
			// Clean dirty input
			$clean = Security::xss_clean($dirty);
		}
	}

	public function demo_config()
	{
		$this->content = View::factory('demo/purifier/config')
			->bind('key', $key)
			->bind('value', $value)
			->bind('before', $before)
			->bind('after', $after)
			;

		// Get HTML Purifier configuration
		$config = Security::htmlpurifier()->config;

		if ($key = Arr::get($_REQUEST, 'key', 'URI.Base'))
		{
			try
			{
				// Get the normal value
				$before = $config->get($key);
			}
			catch (ErrorException $e)
			{
				if ($e->getCode() !== E_USER_WARNING)
				{
					// Most likely not an "undefined value" error, pass it
					throw $e;
				}
			}
		}

		if ($value = Arr::get($_REQUEST, 'value', URL::base(TRUE, TRUE)))
		{
			// Update the configured value
			$config->set($key, $value);

			// And get the new value
			$after = $config->get($key);
		}
	}

} // End Demo_Purifier
