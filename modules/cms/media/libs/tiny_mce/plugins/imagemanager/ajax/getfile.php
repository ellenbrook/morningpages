<?php

$file = ORM::factory('file',arr::get($_POST, 'fileid'));
if(!$file->loaded())
{
	ajax::error('Fejl! Filen blev ikke fundet. Er den blevet slettet i mellemtiden?');
}
$reply = array();
$reply['preview'] = $file->get_icon();
if($file->is_image())
{
	$reply['preview'] = $file->get(250,150);
	list($width, $height) = getimagesize('files/'.$file->filename());
	$reply['width'] = $width;
	$reply['height'] = $height;
}
$reply['alt'] = $file->alt;
$reply['description'] = $file->description;
ajax::success('ok',$reply);
