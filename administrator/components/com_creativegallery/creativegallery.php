<?php
/**
 * Joomla! component creativegallery
 *
 * @version $Id: creativegallery.php 2014-10-01 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Gallery
 * @subpackage com_creativegallery
 * @license GNU/GPLv3
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

/*
 * Define constants for all pages
 */
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
define('JV', (version_compare(JVERSION, '3', 'l')) ? 'j2' : 'j3');

// Require the base controller
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';

// Initialize the controller
$controller	= JControllerLegacy::getInstance('CreativeGallery');

$document = JFactory::getDocument();
$cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/icons_'.JV.'.css';
// $document->addStyleSheet($cssFile, 'text/css', null, array());
// $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/admin.css';
// $document->addStyleSheet($cssFile, 'text/css', null, array());

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();