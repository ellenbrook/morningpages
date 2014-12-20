<?php defined('SYSPATH') OR die('No direct script access.');

I18n::lang(cms::lang());

$cmsslug = cms::slug();

Route::set($cmsslug.'/media', $cmsslug.'/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'cms',
		'action'     => 'media',
		'file'       => NULL,
	));

$controllers = array(
	'controlpanel',
	'content',
	'options',
	'files',
	'navigation',
	'users',
	'super',
	'messages'
);

foreach($controllers as $cont)
{
	Route::set($cmsslug.'-'.$cont, $cmsslug.'/'.$cont.'(/<action>(/<id>(/<typeid>)))')
		->defaults(array(
			'controller' => 'cms_'.$cont
		));
}

Route::set($cmsslug.'jsvars', $cmsslug.'/js(/<action>(/<id>(/<typeid>)))')
	->defaults(array(
		'controller' => 'cms_js'
	));
	
Route::set($cmsslug.'/ajax/widgets', $cmsslug.'/ajax/widgets(/<controller>(/<action>(/<id>)))')
 	->defaults(array(
		'directory' => 'cms/Ajax/Widgets'
	));
Route::set($cmsslug.'/ajax', $cmsslug.'/ajax(/<controller>(/<action>(/<id>)))')
 	->defaults(array(
		'directory' => 'cms/Ajax'
	));

Route::set($cmsslug, $cmsslug.'(/<controller>(/<action>(/<id>)))')
	-> defaults(array(
		'controller' => 'cms'
	));
