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
$db = JFactory::getDBO();
	
	$request = $_REQUEST["req"];
	if ($request == "get") {
		$id = $_REQUEST["id"];
		$query = "SELECT * FROM `#__cg_styles` WHERE `id`=" . $id;
		$db->setQuery($query);
		$data = $db->loadAssocList();
		echo json_encode($data);
	} elseif ($request == "save") {
		$id = $_REQUEST["id"];
		$view_id = $_REQUEST['view_id'];
		$hover_id = $_REQUEST['hover_id'];
		$thumbnail_size = $_REQUEST['thumbnail_size'];
		$thumbnails_count = $_REQUEST['thumbnails_count'];
		$margin = $_REQUEST['margin'];
		$styles = $_REQUEST['styles'];
		$styles_img_hover = $_REQUEST['styles_img_hover'];
		$tags_hover_styles = $_REQUEST['tags_hover_styles'];

		$query = "UPDATE `#__cg_styles` SET `view_id`=" . $view_id . ", `hover_id`=" . $hover_id . 
		", `thumbnail_size`=" . $thumbnail_size . ", `thumbnails_count`=" . $thumbnails_count
		. ", `margin`=" . $margin . ", `styles`='" . addslashes($styles) . "', `styles_img_hover`='" . addslashes($styles_img_hover)
		. "', `tags_hover_styles`='" . addslashes($tags_hover_styles) . "' WHERE `id`=" . $id;
		$db->setQuery($query);
		$result = $db->query();

		if ($result==true) {
			echo "success";
		}
	} elseif ($request == "saveas") {
		$name = $_REQUEST["name"];
		$view_id = $_REQUEST['view_id'];
		$hover_id = $_REQUEST['hover_id'];
		$thumbnail_size = $_REQUEST['thumbnail_size'];
		$thumbnails_count = $_REQUEST['thumbnails_count'];
		$margin = $_REQUEST['margin'];
		$styles = $_REQUEST['styles'];
		$styles_img_hover = $_REQUEST['styles_img_hover'];
		$tags_hover_styles = $_REQUEST['tags_hover_styles'];

		$query = "INSERT INTO `#__cg_styles` (`id`, `name`, `view_id`, `hover_id`, `thumbnail_size`, `thumbnails_count`, `margin`, `styles`, `styles_img_hover`, `tags_hover_styles`) VALUES (NULL, '" . addslashes($name) . "', " . $view_id . ", " . $hover_id . ", " . $thumbnail_size . ", " . $thumbnails_count . ", " . $margin . ", '" . addslashes($styles) . "', '" . addslashes($styles_img_hover) . "', '" . addslashes($tags_hover_styles) . "')";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		//echo $query;
		if ($result==true) {
		 	echo $id;
		}
	}
?>