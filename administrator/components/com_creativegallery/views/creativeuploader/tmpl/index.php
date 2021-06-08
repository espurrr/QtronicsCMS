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
define('_JEXEC',true);
defined('_JEXEC') or die('Restircted access');

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$thumbnails = array(
                    'upload_dir' => $_SERVER["DOCUMENT_ROOT"] . JURI::root(true) . '/media/com_creativegallery/home/' . 'cg_thumbnails/',
                    'upload_url' => JURI::base() . 'media/com_creativegallery/home/' . 'cg_thumbnails/',
                    'max_width' => 80,
                    'max_height' => 80
                );

$upload_dir = JFactory::getApplication()->input->get('upload_dir', 0);
echo $upload_dir;
$upload_dir = $_SERVER["DOCUMENT_ROOT"] . implode('/', explode('-_-', $upload_dir)) . '/';

if (!is_dir($upload_dir)) {
	$upload_dir =  $_SERVER["DOCUMENT_ROOT"] . JURI::root(true) . '/media/com_creativegallery/home/';	
}

$options = array ('upload_dir' => $upload_dir,
	'upload_url' => JURI::base() . 'media/com_creativegallery/home/',
	'thumbnail' => $thumbnails
);


print_r($options);
// $options = array ('upload_dir' => $_SERVER["DOCUMENT_ROOT"] . JURI::root(true) . '/media/com_creativegallery/home/',
// 				  'upload_url' => JURI::base() . 'media/com_creativegallery/home/',
// 				  'thumbnail' => $thumbnails
// 				  );
$upload_handler = new UploadHandler($options);
