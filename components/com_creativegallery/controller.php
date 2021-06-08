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

jimport( 'joomla.application.component.controller' );


/**
 * creative_contact_form Controller
 *
 * @package Joomla
 * @subpackage creative_contact_form
 */
class CreativeGalleryController extends JControllerLegacy {
	
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativegallery';

    public function display($cachable = false, $urlparams = false) {
		parent::display();
    }
}
?>