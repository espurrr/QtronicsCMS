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

class JFormFieldAlbum extends JFormField
{

	protected $type = 'creativegallery';

	function getInput()
	{
		$doc = JFactory::getDocument();
		$fieldName	= $this->name;
		$db = JFactory::getDBO();

		$query = "SELECT `name` as `text`,`id` as `value` FROM `#__cg_albums` WHERE published = '1'";
		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html = array();

		$html[] = "<select name=\"$fieldName\">";
		//$html[] = '<option value="0">'.JText::_("All").'</option>';
		foreach($options AS $o) {
			$html[] = '<option value="'.$o->value.'"'.(($o->value == $this->value) ? ' selected="selected"' : '').'>';
			$html[] = $o->text;
			$html[] = '</option>';
		}
		$html[] = "</select>";

		return implode("", $html);
	}
}
?>
