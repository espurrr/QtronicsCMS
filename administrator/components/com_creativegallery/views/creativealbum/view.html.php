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

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class CreativegalleryViewCreativealbum extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		// Initialiase variables.
		//$this->form		= $this->get('Form');
		$this->item = $this->get('Item');

		///////////

		$data_array = explode( "|", $this->item->tmp_styles );
		$array = array();

		if (count($data_array)>0) {
			foreach ($data_array as $key => $value) {
				$arr = explode( ":", $value);
				if (count($arr)>1) {
					$array[$arr[0]] = $arr[1];
				}
			}
		}
		$this->item->tmp_styles = new stdClass();
		foreach ($array as $key => $value) {
			$this->item->tmp_styles->$key = $value;	
		}

		///////////////////////////

		$data_array1 = explode( "|", $this->item->tmp_tags_hover_styles );
		$array1 = array();
		if (count($data_array1)>0) {
			foreach ($data_array1 as $key => $value) {
				$arr = explode( ":", $value);
				if (count($arr)>1) {
					$array1[$arr[0]] = $arr[1];
				}
			}
		}
		$this->item->tmp_tags_hover_styles = new stdClass();
		if (count($data_array1)>0) {
			foreach ($array1 as $key => $value) {
				$this->item->tmp_tags_hover_styles->$key = $value;	
			}
		}

		/////////////////////////////

		$data_array2 = explode( "|", $this->item->tmp_styles_img_hover );
		$array2 = array();
		foreach ($data_array2 as $key => $value) {
			$arr = explode( ":", $value);
			if (count($arr)>1) {
				$array2[$arr[0]] = $arr[1];
			}
		}

		$this->item->tmp_styles_img_hover = new stdClass();
		foreach ($array2 as $key => $value) {
			$this->item->tmp_styles_img_hover->$key = $value;	
		}

		/////////////////////////////
		$this->images = $this->get('images');
		$this->tags = $this->get('tags');
		$this->imgtags = $this->get('imgtags');
		$this->views = $this->get('views');
		$this->lightboxes = $this->get('lightboxes');
		$this->hovers = $this->get('hovers');
		$this->appears = $this->get('appears');
		$this->styles = $this->get('styles');
		
		//print_r($this->item);
		//$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			// JError::raiseError(500, implode("\n", $errors));
			throw new DatabaseException(implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		// JRequest::setVar('hidemainmenu', true);
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		// Since we don't track these assets at the item level, use the album id.

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Album' ).': <small><small>[ ' . $text.' ]</small></small>','manage.png' );

		// Build the actions for new and existing records.
		if ($isNew)  {
			JToolBarHelper::apply('creativealbum.apply');
			JToolBarHelper::save('creativealbum.save');

			JToolBarHelper::cancel('creativealbum.cancel');
		}
		else {
			JToolBarHelper::apply('creativealbum.apply');
			JToolBarHelper::save('creativealbum.save');
			
			JToolBarHelper::cancel('creativealbum.cancel','close');
		}
	}
}