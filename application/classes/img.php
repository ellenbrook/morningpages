<?php defined('SYSPATH') or die('No direct script access.'); 

abstract class img {
	
	public static function get($filepath, $width = false, $height = false, $crop = false, $dir = 'media/cache/')
	{
		$imgtypes = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			4 => 'swf',
			5 => 'psd',
			6 => 'bmp'
		);
		$nameparts		= explode('/',$filepath);
		$filename		= end($nameparts);
		$path			= implode('/', array_slice($nameparts, 0, count($nameparts) - 1)) . '/';
		$newpath		= $dir;
		
		if ( $height || $width )
		{
			$info 			= getimagesize($filepath);
			$image			= '';
			$final_width	= 0;
			$final_height	= 0;
			list($width_old, $height_old, $type) = $info;
			
			if(!$crop)
			{
				if( $width  == 0 )
				{
					$factor = $height/$height_old;
				}
			    elseif( $height == 0 )
			    {
			    	$factor = $width/$width_old;
				}
				else
				{
					$factor = min( $width / $width_old, $height / $height_old );
				}
				$final_width  = round( $width_old * $factor );
				$final_height = round( $height_old * $factor );
			}
			else
			{
				$final_width = ( $width <= 0 ) ? $width_old : $width;
				$final_height = ( $height <= 0 ) ? $height_old : $height;
			}
			
			$filenameparts = explode('.', $filename);
			$fname = implode('.',array_slice($filenameparts,0,count($filenameparts)-1)) . $final_width . 'x' . $final_height . '.' . $imgtypes[$type];
			if(!file_exists($newpath . $fname))
			{
				switch( $info[2] )
				{
					case IMAGETYPE_GIF:
						$image = imagecreatefromgif($filepath);
						break;
					case IMAGETYPE_JPEG:
						$image = imagecreatefromjpeg($filepath);
						break;
					case IMAGETYPE_PNG:
						$image = imagecreatefrompng($filepath);
						break;
					default:
						return false;
						break;
				}
				
				$image_resized = imagecreatetruecolor( $final_width, $final_height );
				if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) )
				{
					$transparency = imagecolortransparent($image);
					
					if ($transparency >= 0)
					{
						$transparent_color	= imagecolorsforindex($image, $transparency);
						$transparency		= imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
						imagefill($image_resized, 0, 0, $transparency);
						imagecolortransparent($image_resized, $transparency);
					}
					elseif ($info[2] == IMAGETYPE_PNG)
					{
						imagealphablending($image_resized, false);
						$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
						imagefill($image_resized, 0, 0, $color);
						imagesavealpha($image_resized, true);
					}
				}
				imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
				
				$filenameparts = explode('.', $filename);
				$fname = implode('.',array_slice($filenameparts,0,count($filenameparts)-1)) . $final_width . 'x' . $final_height . '.' . $imgtypes[$type];
				
				switch( $info[2] )
				{
					case IMAGETYPE_GIF:
						imagegif($image_resized, $newpath . $fname);
						break;
					case IMAGETYPE_JPEG:
						imagejpeg($image_resized, $newpath . $fname, 100);
						break;
					case IMAGETYPE_PNG:
						imagepng($image_resized, $newpath . $fname);
						break;
					default: return false;
				}
			}
		}
		else
		{
			$fname = $filename;
		}
		
		return url::site($newpath . $fname, 'http');
	}
	
}
