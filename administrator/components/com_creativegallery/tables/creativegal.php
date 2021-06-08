<?php
/**
 * Joomla! component creativegallery
 *
 * @version 2.1.0
 * @author Creative-Solutions.net
 * @package Creative gallery
 * @subpackage com_creativegallery
 * @license GNU/GPLv3
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// import Joomla table library
jimport('joomla.database.table');

class CreativegalTableCreativegal extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__cg_galleries', 'id', $db);
	}
}
