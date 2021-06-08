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

class com_creativegalleryInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // installing module
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_INSTALL_FAILED').'</p>';

       // installing plugin
        $plugin_installer = new JInstaller;
        if($plugin_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
             echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_INSTALL_FAILED').'</p>';
        
        // enabling plugin
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE element = "creativegallery" AND folder = "system"');
        $db->query();
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';

        $db = JFactory::getDBO();
        
        
        
        $sql = 'SELECT `extension_id` AS id, `name`, `element`, `folder` FROM #__extensions WHERE `type` = "module" AND ( (`element` = "mod_creativegallery") ) ';
        $db->setQuery($sql);
        $creative_module = $db->loadObject();
        $module_uninstaller = new JInstaller;
        if($module_uninstaller->uninstall('module', $creative_module->id))
             echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_UNINSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_UNINSTALL_FAILED').'</p>';

         // uninstalling creative image slider plugin
        $db->setQuery("select extension_id from #__extensions where type = 'plugin' and element = 'creativegallery'");
        $creative_plugin = $db->loadObject();
        $plugin_uninstaller = new JInstaller;
        if($plugin_uninstaller->uninstall('plugin', $creative_plugin->extension_id))
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_UNINSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_UNINSTALL_FAILED').'</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEGALLERY_MODULE_INSTALL_FAILED').'</p>';

        $plugin_uninstaller = new JInstaller;
        if(@$plugin_uninstaller->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
            echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEGALLERY_PLUGIN_INSTALL_FAILED').'</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // FUnction execeutes before instal process complete
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        $db = JFactory::getDBO();
        // FUnction execeutes after instal process complete
        //1.0.0 -> 2.0.0 update///////////////////////////////////////////////////////////////////////////////////////
        $query = "SELECT * FROM `#__cg_albums` LIMIT 1";
        $db->setQuery($query);
        $columns_data = $db->LoadAssoc();

        if(is_array($columns_data)) {
            $columns_titles = array_keys($columns_data);
            if(!in_array('max_image_per_page' , $columns_titles)) {
                $query_update = "ALTER TABLE `#__cg_albums` ADD `max_image_per_page` tinyint(3) unsigned NOT NULL DEFAULT '16' AFTER `appear_id`";
                $db->setQuery($query_update);
                $db->query();
            }
        }
    }
}