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

?>
<style>
	.adminlist a {
		color: #a3a5a8;
		font-weight: bold;
	}


</style>


<table class="adminlist" style="width: 100%;margin-top: 12px;clear: both;"><tr><td align="center" valign="middle" id="twoglux_ext_td" style="position: relative;">
	<div id="twoglux_bottom_link"><a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank"><?php echo JText::_( 'COM_CREATIVEGALLERY' ); ?></a> <?php echo JText::_( 'COM_CREATIVEGALLERY_DEVELOPED_BY' ); ?> <a href="http://creative-solutions.net" target="_blank">Creative Solutions</a></div>
	<div style="position: absolute;right: 2px;top: 7px;">
		<a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_RATE_US_LINK' ); ?>" target="_blank" id="twoglux_ext_rate" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_RATE_US_DESCRIPTION' ); ?>">&nbsp;</a>
		<a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank" id="twoglux_ext_homepage" style="margin: 0 2px 0 0px;" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_PROJECT_HOMEPAGE_DESCRIPTION' ); ?>">&nbsp;</a>
		<a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_SUPPORT_FORUM_LINK' ); ?>" target="_blank" id="twoglux_ext_support" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_SUPPORT_FORUM_DESCRIPTION' ); ?>">&nbsp;</a>
	</div>
</td></tr></table>