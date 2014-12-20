<?php
if((bool)$files->count())
{
	$view = view::factory('cms/files/parts/filebrowser-modal-file');
	foreach($files as $file)
	{
		$currentview = $view;
		$currentview->file = $file;
		echo $currentview->render();
	}
}
?>