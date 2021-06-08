<?php 
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

		$request = $_REQUEST["req"];
		// echo 'req=' . $request . ' ' . 'dir=' . $dir . ' ' . 'dir_name=' . $dir_name . ' '  . 'dir_new_name=' . $dir_new_name;
		global $responce;
		$responce = 0;
		global $status;
		$status = 0;
		if ($request == 'mkdir') {
			$dir_name = $_REQUEST["name"];
			$dir = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["dir"];
			//echo $dir . '/' . $dir_name;
			make_dir ($dir . '/' . $dir_name);

		} else if ($request == 'rename') {
			$dir_new_name = $_REQUEST["new_name"];
			$dir_name = $_REQUEST["name"];
			$dir = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["dir"];
			rename($dir . '/' .$dir_name, $dir . '/' . $dir_new_name);
		} else if ($request == 'remove') {
			$dir_name = $_REQUEST["name"];
			$dir = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["dir"];
			if (is_file($dir . '/' . $dir_name)) {
				delTree($dir . '/' . $dir_name);
				delTree($dir . '/cg_thumbnails/' . $dir_name);
			} else {
				delTree($dir . '/' . $dir_name);	
			}
			
		} else if ($request == 'copy') {
			$old_path = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["old_path"];
			$new_path = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["new_path"];
			$number = $_REQUEST["number"];
			$cut_mode = $_REQUEST["cut_mode"];
			$items = array();
			for ($i=0; $i < $number; $i++) { 
				$items[$i] = $_REQUEST['item' . $i];
			}
			copy_dir ($old_path, $new_path, $items);
//			echo $cut_mode;
			if ($cut_mode=="on") {
				foreach ($items as $i) {
					//echo ('removing' . $old_path . '/' . $i);
					delTree( $old_path . '/' . $i );
				}
			}
			
		}
		

		function copy_dir ($old_path, $new_path, $items_to_copy) {
			//echo "old: " . $old_path . " new: " . $new_path;
			global $responce;
			foreach ($items_to_copy as $i) {
				if (is_file($old_path . '/' . $i)) {
					$name_arr = explode('.', $i);
					//echo ($old_path . '/' . $i . ' '. $new_path . '/' . $i);
					copy_file ($old_path . '/' . $name_arr[0], $new_path . '/' . $name_arr[0], $name_arr[1]);
				} elseif (is_dir($old_path . '/' . $i)) {
					$items_in_folder = scandir ($old_path . '/' . $i);
					//print_r($items_in_folder);
					$items_in_folder = array_slice($items_in_folder, 2);
					make_dir ($new_path . '/' . $i);
					$new_path_new = $_SERVER["DOCUMENT_ROOT"] . $responce;
					echo $responce;
					// echo ($old_path . '/' . $i . "   ");
					// echo ($new_path . "   ");
					// echo (print_r($items_in_folder));
					copy_dir($old_path . '/' . $i, $new_path_new, $items_in_folder);
				}
			}
		}


		function delTree($dir = "bsadafassdfsd") { 
			if (is_file($dir)) {
				unlink($dir);
			} else {
				$files = array_diff(scandir($dir), array('.','..')); 
    			foreach ($files as $file) { 
      				(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    			} 
    			return rmdir($dir); 
			}
  		} 
		function make_dir ($directory, $number=0)
		{
			global $responce;
			global $status;
			if ($number == 0) {
				if (is_dir($directory)) {
					make_dir ($directory, 1);	
				} else {
					$status = mkdir( $directory );
					$responce =  str_replace($_SERVER["DOCUMENT_ROOT"], "", $directory);
				}			
			}
			else {
				if (is_dir($directory . '(' . $number . ')')) {
					$number++;
					make_dir ($directory, $number);	
				} else {
					$output_file = $directory . '(' . $number . ')';
					$responce =   str_replace($_SERVER["DOCUMENT_ROOT"], "", $output_file);
					$status = mkdir( $directory . '(' . $number . ')' );
				}	
			}
		}
		function copy_file ($old, $new, $ext, $number=0)
		{
			//echo "old:" . $old . " new:" . $new;
			if ($number == 0) {
				if (is_file($new . '.' . $ext)) {
					copy_file ($old, $new, $ext, 1);	
				} else {
					copy ( $old . '.' . $ext , $new . '.' . $ext);
				}			
			}
			else {
				if (is_file ($new . '(' . $number . ')' . '.' . $ext )) {
					$number++;
					copy_file ($old , $new, $ext , $number);		
				} else {
					copy ( $old . '.' . $ext, $new . '(' . $number . ')' . '.' . $ext);
				}	
			}
		}

		

		$json_resp = array (
			'name' => $responce,
			'status' => $status
		);
		echo json_encode($json_resp);
?>