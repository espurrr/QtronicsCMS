<?php //
/**
 * Joomla! component creativegallery
 *
 * @version 2.1.0
 * @author Creative-Solutions.net
 * @package Creative Gallery
 * @subpackage com_creativegallery
 * @license GNU/GPLv3
 *
 */

//define('_JEXEC',true);
defined('_JEXEC') or die('Restircted access');

		$dir = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["dir"];
		function scan($dir){
			$files = array();
			// Is there actually such a folder/file?
			if(!file_exists($dir)){
				mkdir ($dir, 0777, true); 
			}
			foreach(scandir($dir) as $f) {
				if(!$f || $f[0] == '.') {
					continue; // Ignore hidden files
				}
				if(is_dir($dir . '/' . $f)) {
					// The path is a folder
					if ($f == 'cg_thumbnails') {
						continue;
					}
					$files[] = array(
						"name" => $f,
						"type" => "folder",
						"path" => str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir)  . '/' . $f,
						"items" => scan($dir . '/' . $f), // Recursively get the contents of the folder
						"size" => (count(scandir($dir . '/' . $f))-2)
					);
				}
				else {
					// It is a file

					if ((exif_imagetype($dir . '/' . $f))&&(exif_imagetype($dir . '/' . $f)<=3)) {
						global $img_width, $img_height;
						$image_info = create_thumbnail($dir, $f, 144, 144, exif_imagetype($dir . '/' . $f));
						$files[] = array(
							"name" => $f,
							"type" => "file",
							"path" => str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir) . '/' . $f,
							"size" => filesize($dir . '/' . $f), // Gets the size of this file
							"imagethumb" => $image_info["name"],
							"imgwidth" => $image_info["width"],
							"imgheight" => $image_info["height"]
						);	
					} else {
						$files[] = array(
							"name" => $f,
							"type" => "file",
							"path" => str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir) . '/' . $f,
							"size" => filesize($dir . '/' . $f), // Gets the size of this file
							"imagethumb" => 0
						);	
					}
				}
			}
			return $files;
		}
		function create_thumbnail($path, $filename, $max_width, $max_height, $imagetype)
		{
    		
    		$output_path = $path . '/cg_thumbnails';
    		if (!is_dir($output_path)) {
				mkdir ($output_path);   			
    		}
    		$output_file = $output_path . '/' . $filename;
    		$resp[] = array();
    		$height = '0';
    		$width = '0';
    		if (!is_file($output_file)) {
    			list($orig_width, $orig_height) = getimagesize($path . '/'. $filename);
    	    	$width = $orig_width;
    			$height = $orig_height;
    			# taller
    			if ($height > $max_height) {
        			$width = ($max_height / $height) * $width;
        			$height = $max_height;
    			}
		    	# wider
    			if ($width > $max_width) {
        			$height = ($max_width / $width) * $height;
        			$width = $max_width;
    			}
	    		$image_p = imagecreatetruecolor($width, $height);
	    		if ($imagetype=="3") {
					$image = imagecreatefrompng ($path . '/' . $filename);   			
	    		} else if ($imagetype=="2") {
	    			$image = imagecreatefromjpeg($path . '/' . $filename);
	    		} else if ($imagetype=="1") {
	    			$image = imagecreatefromgif ($path . '/' . $filename);
	    		}
			    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
			    if ($imagetype=="3") {
					imagepng($image_p, $output_file, 9);
	    		} else if ($imagetype=="2") {
	    			imagejpeg($image_p, $output_file, 100);
	    		} else if ($imagetype=="1") {
	    			imagegif($image_p, $output_file, 100);
	    		}
	    	}
	    	else {
	    		list($width, $height, $type, $attr) = getimagesize($output_file);
	    	}
    		$resp["name"] = str_replace($_SERVER["DOCUMENT_ROOT"], "", $output_file) ;
    		$resp["height"] = $height;
    		$resp["width"] = $width;


    		return $resp;
		}
	$response = scan($dir);
	echo json_encode ($response);
?>