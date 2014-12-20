<?php defined('SYSPATH') or die('No direct script access.');

abstract class files {
	
	public static function fixname($name)
	{
		$chars = array(
			'Š'=>'S', 'š'=>'s', 'Đ'=>'D', 'đ'=>'d', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		);
		$name = strtr($name, $chars);
		$name = str_replace(array(' ','_'),'-',$name);
		$name = preg_replace('/^[^a-zA-Z0-9\-]+$/', '', $name);
		return $name;
	}
	
	public static function filebrowser($settings = array())
	{
		$limit = isset($settings["limit"])?$settings["limit"]:0;
		$files = isset($settings["files"])?$settings["files"]:false;
		$btnclass = isset($settings["btnclass"])?$settings["btnclass"]:'btn filebrowserbtn';
		$more = isset($settings["more"])?$settings["more"]:false;
		$btnid = isset($settings['btnid'])?$settings['btnid']:'filebrowser-btn-'.rand(0,99999999);
		$icon = isset($settings['icon'])?$settings['icon']:'glyphicon glyphicon-file';
		$btntext = isset($settings['btntext'])?$settings['btntext']:'Gennemse';
		
		$html = '<div class="filebrowserwrap">';
		$html .= '<button data-bind="filebrowserbtn:addfiles" type="button" id="'.$btnid.'" class="'.$btnclass.'" data-limit="'.$limit.'"'.($more?' '.$more:'').'>';
		$html .= '<span class="'.$icon.'"></span> '.$btntext;
		$html .= '</button>';
		$html .= '</div>';
		return $html;
	}
	
	public static function format_bytes($bytes)
	{
		if ($bytes < 1024)
		{
			return $bytes .' B';
		}
		elseif ($bytes < 1048576)
		{
			return round($bytes / 1024, 2) .' KiB';
		}
		elseif ($bytes < 1073741824)
		{
			return round($bytes / 1048576, 2) . ' MiB';
		}
		elseif ($bytes < 1099511627776)
		{
			return round($bytes / 1073741824, 2) . ' GiB';
		}
		elseif ($bytes < 1125899906842624)
		{
			return round($bytes / 1099511627776, 2) .' TiB';
		}
		elseif ($bytes < 1152921504606846976)
		{
			return round($bytes / 1125899906842624, 2) .' PiB';
		}
		elseif ($bytes < 1180591620717411303424)
		{
			return round($bytes / 1152921504606846976, 2) .' EiB';
		}
		elseif ($bytes < 1208925819614629174706176)
		{
			return round($bytes / 1180591620717411303424, 2) .' ZiB';
		}
		else
		{
			return round($bytes / 1208925819614629174706176, 2) .' YiB';
		}
	}
	
	public static function get_available_filename($dir, $name, $ext)
	{
		while(file_exists($dir . $name . '.' . $ext))
		{
			$lastletter = substr($name, strlen($name)-1,1);
			$secondlastletter = substr($name, strlen($name)-2,1);
			if($secondlastletter == '-' && is_numeric($lastletter))
			{
				$lastletter = $lastletter + 1;
				$name = substr($name, 0, strlen($name)-1) . $lastletter;
			}
			else
			{
				$name = $name . '-' . '2';
			}
		}
		return $name;
	}
	
	public static function randomname($length = 10)
	{
		$alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
		$filename = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $length; $i++)
		{
		    $n = rand(0, $alphaLength);
		    $filename[] = $alphabet[$n];
		}
		return implode($filename);
	}

	public static function upload()
	{
		$dir = 'files/';
		$file = $_FILES['files'];
		$parts = explode('.',$file['name'][0]);
		$ext = strtolower(strtolower(end($parts)));
		$name = mb_strtolower(implode('.',array_slice($parts, 0, count($parts)-1)));
		$name = files::fixname($name);
		$name = self::get_available_filename($dir, $name, $ext);
		$filename = $name . '.' . $ext;
		if(move_uploaded_file($file['tmp_name'][0], $dir. $filename))
		{
			$finfo = new finfo(FILEINFO_MIME);
			$type = $finfo->file($dir . $filename);
			$mime = substr($type, 0, strpos($type, ';'));
			
			$file = ORM::factory('File');
			$file->filename = $name;
			$file->type = $mime;
			$file->ext = $ext;
			$file->created = time();
			try {
				$file->save();
				return $file;
			}
			catch(exception $e)
			{
				unlink($dir . $name);
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
}
