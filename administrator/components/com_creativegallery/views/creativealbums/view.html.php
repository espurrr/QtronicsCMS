<?php
/**
 * Joomla! component creativegallery
 *
 * @version $Id: view.html.php 2014-04-05 14:30:25 svn $
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

class CreativegalleryViewCreativealbums extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 *
	 * @return	void
	 */
    public function display($tpl = null) {
    	
    	$this->items		= $this->get('Items');
    	$this->pagination	= $this->get('Pagination');
    	$this->state		= $this->get('State');
 
    	if(JV == 'j3') {
    		JHtmlSidebar::addFilter(
    				JText::_('JOPTION_SELECT_PUBLISHED'),
    				'filter_published',
    				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
    		);
    	}
    	$this->addToolbar();
    	if(JV == 'j3')
    		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar()
    {
    	JToolBarHelper::addNew('creativealbum.add');
    	JToolBarHelper::editList('creativealbum.edit');
	    	
    	JToolBarHelper::divider();
    	JToolBarHelper::publish('creativealbums.publish', 'JTOOLBAR_PUBLISH', true);
    	JToolBarHelper::unpublish('creativealbums.unpublish', 'JTOOLBAR_UNPUBLISH', true);
    	JToolBarHelper::deleteList('', 'creativealbums.delete', 'JTOOLBAR_DELETE');
	    
    }
    
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields()
    {
    	return array(
    			'sc.name' => JText::_('COM_CREATIVEGALLERY_NAME'),
    			'sc.published' => JText::_('JSTATUS'),
    			'sc.id' => JText::_('JGRID_HEADING_ID')
    	);
    }
}