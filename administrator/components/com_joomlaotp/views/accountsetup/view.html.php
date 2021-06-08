<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Account Setup View
 *
 * @since  0.0.1
 */
class JoomlaOtpViewAccountSetup extends JViewLegacy
{
	function display($tpl = null)
	{
		// Get data from the model
		$this->lists		= $this->get('List');
		//$this->pagination	= $this->get('Pagination');
 
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(500, implode('<br />', $errors));
 
			return false;
		}
		$this->setLayout('accountsetup');
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
	}
 
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
        JToolBarHelper::title(JText::_('mini<span style="color:orange;"><b>O</b></span>range : OTP Verification'), 'mo_otp_logo mo_otp_icon');
	}
	

	
	public function showHelpAndTroubleshooting(){
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ1_LABEL'); 
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ1_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ2_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ2_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ3_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ3_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ4_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ4_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ5_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ5_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ6_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ6_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ7_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ7_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ8_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ8_DESC');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ9_LABEL');
		echo JText::_('COM_JOOMLAOTP_ACCOUNT_SETUP_HELP_AND_TROUBLESHOOTING_FAQ9_DESC');
	}
}