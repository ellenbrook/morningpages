<?php defined('SYSPATH') or die('No direct script access.');

class Model_File extends ORM {
	
	protected $path = 'files/';
	protected $parent_key = 'file_id';
	protected $allowed_exts = array(
		'jpg',
		'jpeg',
		'gif',
		'png'
	);
	protected $allowed_mimes = array(
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/bmp',
	);
	protected $maxsize = 2000000; // 2 mb
	protected $require_login = true;
	protected $limit = 2; // Limit the number of files that can be uploaded
	
	protected $_has_many = array(
		'versions' => array('model'=>'file_version')
	);
	
	protected $_sorting = array(
		'order' => 'DESC'
	);
	
	public function path()
	{
		return url::site($this->path.$this->filename(), true);
	}
	
	public function filename()
	{
		return $this->filename;
	}
	
	public function size()
	{
		try
		{
			$file = filesize($this->path.$this->filename());
			return files::format_bytes($file);
		}
		catch(exception $e)
		{
			return '';
		}
	}
	
	public function is_image()
	{
		$imgtypes = array(
			'image/jpeg',
			'image/gif',
			'image/png',
			'image/bmp',
		);
		return in_array($this->type, $imgtypes);
	}
	
	public function get_icon()
	{
		switch($this->ext)
		{
			case 'txt':
				$icon = 'text.png';
				break;
			case 'zip':
			case 'tar.gz':
			case 'tar.gz2':
			case 'rar':
			case '7z':
				$icon = 'archive.png';
				break;
			case 'doc':
			case 'docx':
			case 'rtf':
			case 'pdf':
				$icon = 'document.png';
				break;
			case 'xls':
			case 'xlsx':
			case 'xml':
				$icon = 'spreadsheet.png';
				break;
			case 'js':
			case 'css':
			case 'php':
				$icon = 'code.png';
				break;
			case 'ogg':
			case 'flv':
			case 'f4v':
			case 'wmv':
				$icon = 'video.png';
				break;
			case 'mp3':
			case 'wav':
				$icon = 'audio.png';
				break;
			default:
				$icon = 'default.png';
				break;
		}
		return cms::url('media/img/file-icons/' . $icon);
	}
	
	public function get_thumb($width = false, $height = false, $crop = false)
	{
		if($this->is_image())
		{
			return $this->get($width, $height, $crop);
		}
		return $this->get_icon();
	}
	
	public function show($width = false, $height = false, $crop = false, $protocol = null)
	{
		if(!$this->is_image())
		{
			return $this->filename();
		}
		if($width || $height)
		{
			$size = $this->versions;
			if($width)
			{
				$size = $size->where('width', '=', $width);
			}
			if($height)
			{
				$size = $size->where('height', '=', $height);
			}
			$size = $size->find();
			if(!$size->loaded())
			{
				$path = img::get($this->path(), $width, $height, $crop, $this->path);
				$pathparts = explode('/', $path);
				$filename = $pathparts[count($pathparts)-1];
				$parentkeyname = $size->parent_key;
				$size->$parentkeyname = $this->id;
				$size->filename = $filename;
				$size->width = $width;
				$size->height = $height;
				$size->time = time();
				$size->save();
			}
			return url::site($this->path.$size->filename,$protocol);
		}
		else
		{
			return url::site($this->path . $this->filename(),$protocol);
		}
	}
	
	public function delete_versions()
	{
		if((bool)$this->versions->count_all()) foreach($this->versions->find_all() as $version)
		{
			$version->delete();
		}
	}
	
	public function delete()
	{
		$this->delete_versions();
		try
		{
			unlink($this->path.$this->filename);
		}
		catch(exception $e)
		{
			// Probably been removed by the user causing the exception? In any case, we don't want to die.
		}
		return parent::delete();
	}
	
	public function upload($files, $limits = array())
	{
		$parts = explode('.',$files['name'][0]);
		$orgfilename = $files['name'][0];
		$size = $files['size'][0];
		$this->ext = strtolower(strtolower(end($parts)));
		if($this->allowed_exts != false && !in_array($this->ext, $this->allowed_exts))
		{
			// Illegal file extention
			throw new Exception_File('Filen "'.$orgfilename.'" kunne ikke uploades. <strong>Filtypen er ikke tilladt</strong>. Kun billeder af følgende filtyper er tilladt: '.implode(', ',$this->allowed_exts));
		}
		if($this->maxsize != false && (!$size || $size == 0 || $size > $this->maxsize))
		{
			// Too big
			throw new Exception_File('Filen "'.$orgfilename.'" kunne ikke uploades da den er for stor! Filer må højest være '.files::format_bytes($this->maxsize));
		}
		if($this->require_login && !user::logged())
		{
			// We only accept files from logged in users
			throw new Exception_User('Du skal være logget ind for at uploade filer. Tjek at du er logget ind og forsøg igen.');
		}
		$this->filename = files::randomname().'.'.$this->ext;
		$i = 2;
		while(file_exists($this->path.$this->filename))
		{
			$this->filename = files::randomname().'.'.$this->ext;
		}
		
		try
		{
			move_uploaded_file($files['tmp_name'][0], $this->path. $this->filename); // Should throw an exception if it fails
			
			$finfo = new finfo(FILEINFO_MIME);
			$type = $finfo->file($this->path . $this->filename);
			$mime = substr($type, 0, strpos($type, ';'));
			if($this->allowed_mimes != false && !in_array($mime, $this->allowed_mimes))
			{
				// Illegal file mime
				throw new Exception_File('Filen "'.$orgfilename.'" kunne ikke uploades. <strong>Filtypen er ikke tilladt</strong>. Kun billeder af følgende filtyper er tilladt: '.implode(', ',$this->allowed_exts));
			}
			
			if($this->require_login)
			{
				$this->user_id = user::get()->id;
			}
			
			$this->type = $mime;
			$this->created = time();
			
			$this->save();
			return $this;
		}
		catch(exception $e)
		{
			// File move failed. Maybe log the error?
			if(file_exists($this->path.$this->filename))
			{
				unlink($this->path.$this->filename);
			}
			throw $e;
		}
	}
	
}
