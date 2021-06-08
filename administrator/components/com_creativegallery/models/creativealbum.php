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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class CreativegalleryModelCreativealbum extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Creativealbum', $prefix = 'CreativealbumTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getImages() 
	{
		// $id = JRequest::getInt("id");
		$id = JFactory::getApplication()->input->get('id','0');

		$req = new JObject();
		$query = "SELECT `id`, `id_album`, `path`, `name`, `title`, `description`, `published`, `link`, `target`, `ordering` FROM `#__cg_images` WHERE `id_album` = " . $id . ' ORDER BY `ordering` ASC';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$list = $db->loadAssocList();

		return $list;
	}


	public function getImgTags() 
	{
		// $id = JRequest::getInt("id");
		$id = JFactory::getApplication()->input->get('id','0');
		$req = new JObject();
		$query = "SELECT ci.id as id, it.id as id_tag, it.name as tag_name FROM `#__cg_images` as ci
				  LEFT JOIN `#__cg_img_tag_list` as tl ON tl.id_img = ci.id
				  LEFT JOIN `#__cg_imagetags` as it ON it.id = tl.id_tag
				  WHERE ci.`id_album` = " . $id;
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$id_tag_list = $db->loadAssocList();
		$id_tagid_tagname = array();
		foreach ($id_tag_list as $num => $id_list) {
			// if (($id_list['id_tag']==NULL)||($id_list['tag_name']==NULL)) {
			// 	continue;
			// }
			$id_tagid_tagname[$id_list['id']][$id_list['id_tag']] = $id_list['tag_name'];
		}
		return $id_tagid_tagname;
		//return $id_tag_list;
		//return $query;
	}

	public function getViews() 
	{
		$query = "SELECT * FROM `#__cg_views` WHERE 1 ORDER BY id ASC";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$id_name = array();
		foreach ($result as $num => $list) {
			$id_name[$list['id']] = $list['name'];
		}
		return $id_name;
	}
	
	public function getLightboxes() 
	{
		$query = "SELECT * FROM `#__cg_lightbox` WHERE 1";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$id_name = array();
		foreach ($result as $num => $list) {
			$id_name[$list['id']] = $list['name'];
		}
		return $id_name;
	}

	public function getHovers() 
	{
		$query = "SELECT * FROM `#__cg_hover` WHERE 1";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$id_name = array();
		foreach ($result as $num => $list) {
			$id_name[$list['id']] = $list['name'];
		}
		return $id_name;
	}

	public function getAppears() 
	{
		$query = "SELECT * FROM `#__cg_appears` WHERE 1";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$id_name = array();
		foreach ($result as $num => $list) {
			$id_name[$list['id']] = $list['name'];
		}
		return $id_name;
	}

	public function getStyles() 
	{
		$query = "SELECT `id`, `name` FROM `#__cg_styles`";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$id_name = array();
		foreach ($result as $num => $list) {
			$id_name[$list['id']] = $list['name'];
		}
		return $id_name;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */

	public function getForm($data = array(), $loadData = true) 
	{
		// //echo "bla";
		// // Get the form.
		// $form = $this->loadForm('com_creativegallery.creativealbum', 'creativealbum', array('control' => 'jform', 'load_data' => $loadData));
		// if (empty($form)) 
		// {
		// 	return false;
		// }
		// return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_creativegallery.edit.creativealbum.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to save field
	 */
	function saveAlbum()
	{
		$date = new JDate();
		// $id = JRequest::getInt('id',0);
		$id = JFactory::getApplication()->input->get('id', 0);

		// $data = $_POST["cg_post"];
		$data = JFactory::getApplication()->input->post->get("cg_post", array(), 'array');

		// echo "<pre>";
		// print_r($data["album"]);
		// echo "</pre>";
		// print_r(array_keys($post));
		
		

		$album_data = $data["album"];
		
		$ordering = $data["album"]["ordering"];
		$ordering_arr = explode(" ", $ordering);
		
		// $tags = $data["album"]["tags"];
		// $tags_arr = explode(" ", $tags);
		$req = new JObject();

		$req->name = $album_data["name"];
		$req->description = $album_data["description"];
		$req->prev_img = $album_data["preview_image"];
		$req->view_id = $album_data["view_id"];
		$req->lightbox_id = $album_data["lightbox_id"];
		$req->max_image_per_page = $album_data["max_image_per_page"];
		$req->hover_id = $album_data["hover_id"];
		$req->appear_id = $album_data["appear_id"];
		$req->thumbnail_size = $album_data["thumbnail_size"];
		$req->thumbnails_count = $album_data["thumbnails_count"];
		$req->margin = $album_data["margin"];
		
		// img options


		//print_r($album_data["tmp_styles"]);
		// should be
		$tmp_styles = '';
		foreach($album_data["tmp_styles"] as $k => $v) {
			$k = str_replace(array("|",":"),'',$k);
		 	$v = str_replace(array("|",":"),'',$v);
		 	$tmp_styles .= $k . ':' . $v . '|';
		}
		
		$tmp_styles = trim($tmp_styles, '|');

		$req->tmp_styles = $tmp_styles;

		///////////////////////////////////
		$tmp_tags_hover_styles  = '';
		foreach($album_data["tmp_tags_hover_styles"] as $k => $v) {
			$k = str_replace(array("|",":"),'',$k);
		 	$v = str_replace(array("|",":"),'',$v);
		 	$tmp_tags_hover_styles  .= $k . ':' . $v . '|';
		}
		
		$tmp_tags_hover_styles = trim($tmp_tags_hover_styles , '|');

		$req->tmp_tags_hover_styles = $tmp_tags_hover_styles;

		///////////////////////////////////
		$tmp_styles_img_hover  = '';
		foreach($album_data["tmp_styles_img_hover"] as $k => $v) {
			$k = str_replace(array("|",":"),'',$k);
		 	$v = str_replace(array("|",":"),'',$v);
		 	$tmp_styles_img_hover  .= $k . ':' . $v . '|';
		}
		
		$tmp_styles_img_hover  = trim($tmp_styles_img_hover , '|');

		$req->tmp_styles_img_hover = $tmp_styles_img_hover;

		///////////////////////////////////

		$req->published = 1;
	
		$response = array( 0=>"no", "1"=>0);
		if($id == 0) {
			$req->id = NULL;
			
			if (!$this->_db->insertObject( '#__cg_albums', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FORM_SAVED";
				$response[0] = $cis_error;
				return $response;
			}
			$new_insert_id = $this->_db->insertid();
			$response[1] = $new_insert_id;
			$id = $new_insert_id;
		} else { //else update the record
			$req->id = $id;
			if (!$this->_db->updateObject( '#__cg_albums', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FORM_SAVED";
				$response[0] = $cis_error;
				return $response;
			}
		}
		$remove_query = "DELETE FROM `#__cg_images` WHERE `id_album`=" . $id;
		$rm = JFactory::getDBO();
		$rm->setQuery($remove_query);
		$rm->query();
		//echo $remove_query;

		$img_tags = array();

		$start_id = 0;
		$end_id = 0;
		if ($ordering_arr[0]!="") {
			$keys = array_keys($data['item_0']);
			$query = "INSERT INTO `#__cg_images` (`id`, `id_album`";
			foreach ($keys as $key => $value) {
				if ($value=="tags") { continue;}
				if ($value=="publish") { $value = 'published';}
				$query = $query . " ,`" . $value . "`";
			}
			$query = $query . ", `ordering` ) VALUES";
			$img_list = array();
		
			$counter = 0;
			foreach ($ordering_arr as $i => $value) {
				$query =  $query . '(NULL, ' . "'" . $id  . "'" . ', ';
				foreach ($data[$value] as $j => $j_val) {
					// echo $j . '<br>';
			 		if ($j == "tags") {
			 			//echo $i;

			 			//print_r($j_val);
			 			$img_tags[] = $j_val;
			 			continue;
			 		}
			 		if ($j=="path") {
			 			$img_list[] = $j_val;
			 		}
			 		$query = $query . "'" . addslashes($j_val) ."'" . ', ';
			 	}
			 	$query = $query . "'" . $i  . "'" . '),';
				$counter ++ ;
			}
			$query = trim($query, ",");
			$query = $query . ";";
			// echo $query;
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$db->query();
			$start_id = $db->insertid();
			$end_id = $start_id + $counter - 1;
		}
		// echo $start_id . "  " . $end_id . " " . $counter;
		
		$remove_query = "DELETE FROM `#__cg_img_tag_list` WHERE `id_img`>=" . $start_id . " AND `id_img`<=" . $end_id;
		$rm = JFactory::getDBO();
		$rm->setQuery($remove_query);
		$rm->query();
		
		$query = "SELECT `id` FROM `#__cg_imagetags`";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$alltags=array();
		foreach ($result as $key => $value) {
			$alltags[] = $value['id'];
		}



		$query = "INSERT INTO `#__cg_img_tag_list` (`id_img`, `id_tag`) VALUES ";

		$tags_count =0;
		foreach ($img_tags as $num => $tags) {
			if ($tags=="") {
				continue;
			}
			$tags_list = explode(" ", $tags);
			foreach ($tags_list as $number => $tag_id) {
				if (!in_array($tag_id, $alltags)) {
					continue;
				}
				$img_id = $start_id + $num;
				$query = $query . "(" . $img_id . "," . $tag_id . "),"  ;
			}
			$tags_count++;
		}
		if ($tags_count!=0) {
			$query = trim($query, ",");
			$db = JFactory::getDBO();
			// echo $query;
			$db->setQuery($query);
			$db->query();
		}



		// $remove_query = "DELETE FROM `#__cg_tag_album_list` WHERE `id_album`=" . $id;
		// $rm = JFactory::getDBO();
		// $rm->setQuery($remove_query);
		// $rm->query();
		
		
		// $query = "INSERT INTO `#__cg_tag_album_list` (`id`, `id_album`, `id_tag`) VALUES ";
		// if ($tags_arr[0]!="") {
		// 	foreach ($tags_arr as $i => $value) {
		// 		$query = $query . '(NULL, ' . $id . ' , ' . $tags_arr[$i] . ' ),';
		// 	}
		// 	$query = trim($query, ",");
		// 	$query = $query . ";";
		// 	//echo $query;
		// 	$db = JFactory::getDBO();
		// 	$db->setQuery($query);
		// 	$db->query();
		// }

		

		
		
		return $response;
	}

	


}