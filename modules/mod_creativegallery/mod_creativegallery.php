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

// get a parameter from the module's configuration
$module_id = $module->id;
$album_id = $params->get('album_id',1);
//$class_suffix = $params->get('class_suffix','');

//include helper class
require_once JPATH_SITE.'/components/com_creativegallery/helpers/helper.php';

$cg_class = new CreativegalleryHelper;
$cg_class->album_id = $album_id;
$cg_class->type = 'component';
// $ccf_class->class_suffix = '';
// $ccf_class->module_id = 0;
echo $cg_class->render_html();
//echo $album_id;


?>