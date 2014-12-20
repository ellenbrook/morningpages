<?php
include(dirname(__FILE__).'/../functions.php');

$query = arr::get($_POST, 'query','');
$files = ORM::factory('file')->where('filename','LIKE','%'.$query.'%')->find_all();
$filenames = array();
if((bool)$files->count()) foreach($files as $file)
{
	echo filehelper::tableoutput($file);
}
