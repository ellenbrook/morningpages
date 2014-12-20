<?php

$file = ORM::factory('file',arr::get($_POST, 'fileid'));
if(!$file->loaded())
{
	ajax::error('Fejl! Filen blev ikke fundet. Er den blevet slettet i mellemtiden?');
}
$width = arr::get($_POST, 'width', false);
if(!$width)
{
	$width =$file->width;
}
$height = arr::get($_POST, 'height', false);
if(!$height)
{
	$height =$file->height;
}
$file = $file->get($width, $height);
$class = '';
if(arr::get($_POST,'float','') == 'left')
{
	$class = 'alignleft';
}
if(arr::get($_POST,'float','') == 'right')
{
	$class = 'alignright';
}
$file = '<img src="'.$file.'" width="'.$width.'" height="'.$height.'" alt="'.arr::get($_POST, 'alt','').'" class="'.$class.'" />';

ajax::success('ok',array('file' => $file));