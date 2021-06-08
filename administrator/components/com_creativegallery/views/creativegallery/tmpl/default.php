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

$document = JFactory::getDocument();

$document->addStyleSheet("components/com_creativegallery/assets/css/reset.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/creativegallery/icons.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/creativegallery/main.css");




?>
<div id="cg_main_wrapper">
	<a href="http://www.creative-solutions.net" target="blank" class="logo">
		<img src="components/com_creativegallery/assets/images/logo/logo.png" alt="creative solutions logo">
	</a>
	<div class="background1"></div>

	<div class="wrapper">
		<h1>Creative Gallery</h1>

<!--		<p class="description">Welcome To Creative Gallery</p>-->

		<div class="cpanel left">
			<a href="index.php?option=com_creativegallery&view=creativealbums" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_ALBUMS' ); ?>">
				<div class="sprite sprite-services"></div>
				<span><?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_ALBUMS' ); ?></span>
			</a>
		</div>
		<div class="cpanel right">
			<a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_SUPPORT_FORUM_LINK' ); ?>" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_SUPPORT_FORUM_DESCRIPTION' ); ?>">
				<div class="sprite sprite-support"></div>
				<span style="padding:8px 0"><?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_SUPPORT_FORUM' ); ?></span>
			</a>
		</div>
		<div class="cpanel right">
			<a href="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_RATE_US_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_RATE_US_DESCRIPTION' ); ?>">
				<div class="sprite sprite-star"></div>
				<span><?php echo JText::_( 'COM_CREATIVEGALLERY_SUBMENU_RATE_US' ); ?></span>
			</a>
		</div>
		<?php include (JPATH_BASE.'/components/com_creativegallery/helpers/footer.php'); ?>
	</div>
</div>
