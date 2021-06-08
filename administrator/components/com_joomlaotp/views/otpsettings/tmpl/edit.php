<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');
 
if(MoOtpUtility::is_curl_installed()==0){ ?>
	<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
<?php
}?>
<div class="form-horizontal">
	<ul class="nav nav-tabs" id="myTabTabs">
		<li class=""><a href="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup'); ?>"><?php echo JText::_('COM_JOOMLAOTP_TAB2_ACCOUNT_SETUP'); ?></a></li>
		<li class=""><a href="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=license'); ?>" ><?php echo JText::_('COM_JOOMLAOTP_TAB6_LICENSING_PLANS'); ?></a></li>
		<li class=""><a href="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=faqs'); ?>" ><?php echo JText::_('COM_JOOMLAOTP_TAB7_HELP'); ?></a></li>
		<li class=""><a href="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=confg'); ?>" ><?php echo JText::_('COM_JOOMLAOTP_TAB8_CONFIGURATION'); ?></a></li>
		<li class=""><a href="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=otpmsg'); ?>" ><?php echo JText::_('COM_JOOMLAOTP_TAB6_OTPMSG'); ?></a></li>
	</ul>
</div>