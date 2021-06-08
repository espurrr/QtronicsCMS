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

class CreativegalleryControllerCreativealbum extends JControllerForm
{
	function __construct($default = array()) {
		parent::__construct($default);
	
		$this->registerTask('save', 'saveAlbum');
		$this->registerTask('apply', 'saveAlbum');
		$this->registerTask('save2new', 'saveAlbum');
	}

	function saveAlbum() {
		// $id = JRequest::getInt('id',0);
		$id = JFactory::getApplication()->input->get('id', 0);
		$model = $this->getModel('creativealbum');
	
		$response = $model->saveAlbum();

		$msg_string = $response[0];
		$insert_id = $response[1];

		$id = ($id == 0 && $insert_id != 0) ? $insert_id : $id; 

		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVEGALLERY_SAVED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if($_REQUEST['task'] == 'apply' && $id != 0)
			$link = 'index.php?option=com_creativegallery&view=creativealbum&layout=edit&id='.$id;
		elseif($_REQUEST['task'] == 'save')
			$link = 'index.php?option=com_creativegallery&view=creativealbums';
		else
			$link = 'index.php?option=com_creativegallery&view=creativealbums';
		$this->setRedirect($link, $msg, $msg_type);
	}
}
