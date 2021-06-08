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
	$type = $_REQUEST["type"];
	if ($type == "album") {
		if ($request == "get") {
			$query = "SELECT * FROM `#__cg_albumtags`";
			$db->setQuery($query);
			$data = $db->loadAssocList();
			echo json_encode($data);
		} elseif ($request == "remove") {
			$tag_id = $_REQUEST["tag_id"];
			$query = "DELETE FROM `#__cg_albumtags` WHERE `id`=" . $tag_id;
			$db->setQuery($query);
			$db->execute();
			$query = "DELETE FROM `#__cg_img_tag_list` WHERE `tag_id`=" . $tag_id;
			$db->setQuery($query);
			$db->execute();
		} elseif ($request == "update") {
			$tag_id = $_REQUEST["tag_id"];
			$tag_name = $_REQUEST["tag_name"];
			$query_exists = "SELECT * FROM `#__cg_albumtags` WHERE `name`='" . $tag_name ."'";
			$db->setQuery($query_exists);
			$data = $db->loadAssocList();
			if (sizeof($data)==0) {
		 		$query = "UPDATE `#__cg_albumtags` SET `name`='".$db->escape($tag_name). "' WHERE `id`=".$tag_id;
				$db->setQuery($query);
				$db->execute();
				echo "OK";
		 	} else {
		 		echo "FAILURE";
		 	};
		} elseif ($request == "new") {
			$tag_name = $_REQUEST["tag_name"];
			$query_exists = "SELECT * FROM `#__cg_albumtags` WHERE `name`='" . $tag_name ."'";
			$db->setQuery($query_exists);
			$data = $db->loadAssocList();
			if (sizeof($data)==0) {
		 		$query = "INSERT INTO `#__cg_albumtags` (`id`, `name`) VALUES ( NULL, '" .$db->escape($tag_name). "' )";
			$db->setQuery($query);
			$db->execute();
			echo "OK";
		} else {
			echo "FAILURE";
		};
	}

	} elseif ($type=="image") {
		if ($request == "get") {
			$query = "SELECT * FROM `#__cg_imagetags`";
			$db->setQuery($query);
			$data = $db->loadAssocList();
			echo json_encode($data);
		} elseif ($request == "remove") {
			$tag_id = $_REQUEST["tag_id"];
			$query = "DELETE FROM `#__cg_imagetags` WHERE `id`=" . $tag_id;
			$db->setQuery($query);
			$db->execute();
		} elseif ($request == "update") {
			$tag_id = $_REQUEST["tag_id"];
			$tag_name = $_REQUEST["tag_name"];
			$query_exists = "SELECT * FROM `#__cg_imagetags` WHERE `name`='" . $tag_name ."'";
			$db->setQuery($query_exists);
			$data = $db->loadAssocList();
			if (sizeof($data)==0) {
		 		$query = "UPDATE `#__cg_imagetags` SET `name`='".$db->escape($tag_name). "' WHERE `id`=".$tag_id;
				$db->setQuery($query);
				$db->execute();
				echo "OK";
		 	} else {
		 		echo "FAILURE";
		 	};
		} elseif ($request == "new") {
			$tag_name = $_REQUEST["tag_name"];
			$query_exists = "SELECT * FROM `#__cg_imagetags` WHERE `name`='" . $tag_name ."'";
			$db->setQuery($query_exists);
			$data = $db->loadAssocList();
			if (sizeof($data)==0) {
		 		$query = "INSERT INTO `#__cg_imagetags` (`id`, `name`) VALUES ( NULL, '" .$db->escape($tag_name). "' )";
			$db->setQuery($query);
			$db->execute();
			echo "OK";
			} else {
				echo "FAILURE";
			};
		}
	}


		
?>