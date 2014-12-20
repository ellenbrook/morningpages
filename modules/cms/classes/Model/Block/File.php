<?php defined('SYSPATH') or die('No direct script access.');

class Model_Block_File extends ORM {
	
	protected $_belongs_to = array(
		'block' => array(),
		'file' => array()
	);
	
	public function info()
	{
		$info = $this->file->info();
		//$info['id'] = $this->id;
		$info['description'] = $this->description;
		$info['alt'] = $this->alt;
		$info['block_id'] = $this->block_id;
		return $info;
	}
	
	public function copy($newblock_id = false)
	{
		$new = ORM::factory('Block_File');
		$new->block_id = $this->block_id;
		if($newblock_id)
		{
			$new->block_id = $newblock_id;
		}
		$new->file_id = $this->file_id;
		$new->description = $this->description;
		$new->alt = $this->alt;
		$new->save();
		return $new;
	}
	
}
