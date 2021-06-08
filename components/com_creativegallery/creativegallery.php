<?php
/**
 * Joomla! component Creative Gallery
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
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

require_once JPATH_COMPONENT . '/helpers/helper.php';

$controller	= JControllerLegacy::getInstance('CreativeGallery');
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();