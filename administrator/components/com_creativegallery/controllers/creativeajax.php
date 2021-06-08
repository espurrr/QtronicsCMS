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

// no direct access
defined('_JEXEC') or die('Restircted access');

jimport('joomla.application.component.controllerform');

class CreativegalleryControllerCreativeajax extends JControllerForm
{
	public function scan_dir() 
	{
    	// Set view
        JFactory::getApplication()->input->set('layout', 'scandir');
    	// JRequest::setVar('layout', 'scandir');
        JFactory::getApplication()->input->set('format', 'json');
    	// JRequest::setVar('format', 'json');
    	parent::display();
	}
	public function directory() 
	{
    	// Set view
        JFactory::getApplication()->input->set('layout', 'directory');
    	// JRequest::setVar('layout', 'directory');
        JFactory::getApplication()->input->set('format', 'json');
    	// JRequest::setVar('format', 'json');
    	parent::display();
	}
}
