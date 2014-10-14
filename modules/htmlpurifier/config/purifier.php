<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'finalize' => TRUE,
	'preload'  => FALSE,
	'settings' => array(
		'Attr.AllowedRel' => array('nofollow')
		/**
		 * Use the application cache for HTML Purifier
		 */
		// 'Cache.SerializerPath' => APPPATH.'cache',
	),
);
