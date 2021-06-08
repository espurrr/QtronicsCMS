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

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');

class plgSystemCreativegallery extends JPlugin {

    function __construct( &$subject ) {
        parent::__construct( $subject );

        // load plugin parameters and language file
        $this->_plugin = JPluginHelper::getPlugin( 'system', 'creativegallery' );
        $this->_params = json_decode( $this->_plugin->params );
        JPlugin::loadLanguage('plg_system_creativegallery', JPATH_ADMINISTRATOR);
    }

    function ccf_make_form($m) {
        $album_id = (int) $m[2];

        //include helper class
        require_once JPATH_SITE.'/components/com_creativegallery/helpers/helper.php';

        $ccf_class = new CreativegalleryHelper;
        $ccf_class->album_id = $album_id;
        $ccf_class->type = 'plugin';
        //$ccf_class->class_suffix = 'ccf_plg';
        //$ccf_class->module_id = $this->plg_order;
        $this->plg_order ++;

       // return "blabla";
        return  $ccf_class->render_html();
    }

    function render_styles_scripts($id) {

        //return;
        $document = JFactory::getDocument();
        $content = JResponse::getBody();
        $db = JFactory::getDBO();

        $version = '3.0.0';
        $scripts = '';

        //check if component or module loaded CCF scripts already, if no, load them
        if (strpos($content,'components/com_creativegallery/assets/css/reset.css') === false) {

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/reset.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/main.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/loader.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/generate.css.php?id_album='.$id;
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/filters.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/effects.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/widgets/creative-lightbox/creative-lightbox.css';
            $scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/libs/jquery-2.2.3.min.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/libs/es6-promise.min.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/widgets/creative-lightbox/creative-lightbox-min.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/creative-gallery.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/widgets/small scripts/direction-aware-caption.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

            $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/main.js';
            $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

        }

        $content = str_replace('</head>', $scripts . '</head>', $content);
        return $content;
    }


    
    function onAfterRender() {
        $mainframe = JFactory::getApplication();
        if($mainframe->isAdmin())
            return;

        $plugin = JPluginHelper::getPlugin('system', 'creativegallery');
        $pluginParams = json_decode( $plugin->params );

        $content = JResponse::getBody();

        //If shortcode found, then add scripts
        if(preg_match('/(\[creativegallery id="([0-9]+)"\])/s',$content, $matches))
             $content = $this->render_styles_scripts($matches[2]);

        // if there is no shortcode, and module or component does not load CCF as well, then return
        if(!preg_match('/(\[creativegallery id="([0-9]+)"\])/s',$content))
            return;
      
        //if shortcode found, render form
        if(preg_match('/(\[creativegallery id="([0-9]+)"\])/s',$content)) {
            $this->plg_order = 10000;
            //plugin 
            $content = preg_replace_callback('/(\[creativegallery id="([0-9]+)"\])/s',array($this, 'ccf_make_form'),$content);
        }

        //if any ccf script have rendered recaptcha, then make js corrections
        // if($rcp_scripts_included)
        //     $content = $this->render_ccf_recaptcha_scripts($content);
      
        JResponse::setBody($content);
    }

}