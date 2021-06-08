<?php
/**
 * Joomla! component creativegallery
 *
 * @version $Id: controller.php 2014-10-01 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Gallery
 * @subpackage com_creativegallery
 * @license GNU/GPLv3
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );

class CreativegalleryController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativegallery';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	// public function display1()
	// {
	// 	// Load the submenu.
	// 	echo 'works';
	// }
	public function display($cachable = false, $urlparams = false)
	{
		CreativegalleryHelper::addSubmenu( 'Overview', 'creativegallery');
		CreativegalleryHelper::addSubmenu( 'Albums', 'creativealbums');

		parent::display();

		return $this;
	}
}
