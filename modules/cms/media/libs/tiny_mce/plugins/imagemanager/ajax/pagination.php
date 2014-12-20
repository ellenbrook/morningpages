<?php
include(dirname(__FILE__).'/../functions.php');

$page = arr::get($_GET, 'page', 1);
$limit = 20;
$files = ORM::factory('file')->limit($limit)->offset(($page-1)*$limit)->find_all();

if((bool)$files->count())
{
	foreach($files as $file)
	{
		echo filehelper::tableoutput($file);
	}
}
