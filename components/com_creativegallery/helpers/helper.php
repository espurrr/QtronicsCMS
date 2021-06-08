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


    define('FILE_PATH', dirname(__FILE__) . '/');
    define('ROOT_PATH', dirname(__DIR__) . '/');
    require(FILE_PATH . 'parser.php');

class CreativegalleryHelper
{
    //function to add scripts/styles
    var $tags_array = array();

    var $hovers_array_img = array(
        '',  //0
        'color', //1
        'gray', //2
        'blur', //3
        'brightness', //4
        'sepia', //5
        'contrast', //6
        'hue-rotate', //7
        'brightness1', //8
        'invert', //9
        'saturate', //10
        '', //11
        'zoom', //12
        'rotate', //13
        '' //14
    );

    var $hovers_array_li = array (
        '', //0
        '', //1
        '', //2
        '', //3
        '', //4
        '', //5
        '', //6
        '', //7
        '', //8
        '', //9
        '', //10
        'caption1', //11
        '', //12
        '', //13
        'direction-aware', //14
    );

    var $preview_height;

    private function add_scripts($id) {
        $document = JFactory::getDocument();

        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/filters.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());

        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/effects.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());

        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/main.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());

        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/reset.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());


        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/loader.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());

        $cssFile = JURI::base(true).'/components/com_creativegallery/assets/css/widgets/creative-lightbox/creative-lightbox.css';
        $document->addStyleSheet($cssFile, 'text/css', null, array());


        $cssFile = JURI::base(true).'/components/com_creativegallery/generate.css.php?id_album='.$id;
        $document->addStyleSheet($cssFile, 'text/css', null, array());

        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/libs/jquery-2.2.3.min.js';
        $document->addScript($jsFile);
        
        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/libs/es6-promise.min.js';
        $document->addScript($jsFile);

        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/widgets/creative-lightbox/creative-lightbox-min.js';
        $document->addScript($jsFile);

        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/creative-gallery.js';
        $document->addScript($jsFile);

        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/widgets/small scripts/direction-aware-caption.js';
        $document->addScript($jsFile);

        $jsFile = JURI::base(true).'/components/com_creativegallery/assets/js/main.js';
        $document->addScript($jsFile);
    }

    private function get_album_info ($id)
    {
        $query = "SELECT ca.name as album_name, ca.description as album_description, ca.prev_img as album_prev_img, ca.view_id as view_id, 
						 ca.lightbox_id as lightbox_id, ca.max_image_per_page as max_image_per_page, ca.hover_id as hover_id, ca.appear_id as appear_id, ca.thumbnail_size as thumbnail_size, 
						 ca.thumbnails_count as thumbnails_count, ca.margin as margin, ca.tmp_styles as tmp_styles, 
						 ca.tmp_tags_hover_styles as tmp_tags_hover_styles,ca.tmp_styles_img_hover as tmp_styles_img_hover FROM `#__cg_albums` as ca
				  WHERE ca.`id` =" . $id ;
        //echo $query;
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $list = $db->loadAssoc();
        return $list;
    }

    private function get_album_images ($id)
    {
        $query = "SELECT `ci`.`id` as `img_id`, 
                         `ci`.`path` as `img_path`, 
                         `ci`.`name` as `img_name`, 
                         `ci`.`title` as `img_title`, 
                         `ci`.`link` as `img_link`, 
                         `ci`.`target` as `img_target`, 
                         `ci`.`description` as `img_description`, 
                         `ci`.`ordering` as `img_ordering`, 
                         `citl`.`id_tag` as `img_tag`,
                         `cit`.`name` as `tag_name`
                  FROM `#__cg_images` as ci
				  LEFT JOIN `#__cg_img_tag_list` as `citl` ON `citl`.`id_img` = `ci`.`id`
				  LEFT JOIN `#__cg_imagetags` as `cit` ON `cit`.`id` = `citl`.`id_tag`
				  WHERE ci.`id_album` =" . $id . " ORDER BY `ci`.`ordering` ASC";
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $filtered_array = array();
        $list = $db->loadAssocList();
        $tags = array();
        foreach ($list as $key => $value) {
            $ordering = $value['img_ordering'];
            $img_tag = $value['img_tag'];
            $tag_name = $value['tag_name'];
            if (!in_array($img_tag, $tags) && $img_tag!='') {
                array_push($tags, $img_tag);
                array_push($this->tags_array, array($img_tag, $tag_name));
            }
            if (count($filtered_array)>$ordering) {
                $filtered_array[$ordering]['img_tag'] = $filtered_array[$ordering]['img_tag'] . ' ' . $value['img_tag'];
            } else {
                $filtered_array[$ordering] = $value;
            }
        }
        return $filtered_array;
    }

    private function render_album ($id)
    {
        $album_info = $this->get_album_info($id);

        if($this->type != 'plugin') {
            $this->add_scripts($id);
        }

        $wrapper = new template_parser;

        $wrapper->get_tpl(FILE_PATH . 'templates/wrapper.tpl');

        $wrapper->set_tpl('{MAIN_PATH}',  JURI::root(false));
        $wrapper->set_tpl('{ALBUM_ID}',  $id);
        $wrapper->set_tpl('{ALBUM_NAME}',  $album_info['album_name']);
        $wrapper->set_tpl('{VIEW_ID}',  $album_info['view_id']);
        $wrapper->set_tpl('{MAX_IMAGE_PER_PAGE}',  $album_info['max_image_per_page']);
        $wrapper->set_tpl('{POPUP_ID}',  $album_info['lightbox_id']);
        $wrapper->set_tpl('{ALBUM_HOVER}',  $album_info['hover_id']);
        $wrapper->set_tpl('{ALBUM_APPEAR}',  $album_info['appear_id']);
        $wrapper->set_tpl('{ALBUM_SIZE}',  $album_info['thumbnail_size']);
        $wrapper->set_tpl('{ALBUM_COUNT}',  $album_info['thumbnails_count']);
        $wrapper->set_tpl('{ALBUM_MARGIN}',  $album_info['margin']);

        $tmp_styles = $album_info['tmp_styles'];
        $tmp_styles_arr = explode('|', $tmp_styles);


        $this->tags_enabled = "1";
        foreach ($tmp_styles_arr as $i => $value) {
            $current_property = explode(':', $value);
            $prop_key = $current_property[0];
            $prop_val = $current_property[1];
            switch ($prop_key) {
                case 'prev_speed':
                case 'img_border_radius':
                case 'img_border_color':
                case 'img_icon_type':
                case 'img_icon_effect':
                case 'img_icon_top1':
                case 'img_icon_left1':
                case 'img_icon_top2':
                case 'img_icon_left2':
                case 'lightbox_type':
                case 'lightbox_transition_delay':
                case 'lightbox_thumbnail_width':
                case 'lightbox_thumbnail_height':
                case 'lightbox_caption_enebled':
                case 'lightbox_caption_height':
                case 'lightbox_image_loading_timeout':
                case 'lightbox_carousel_images_margin':
                case 'lightbox_carousel_map_size':
                case 'lightbox_carousel_map_icons_max_visible':
                case 'lightbox_autoplay_enebled':
                case 'lightbox_carousel_slide_speed':
                    # code...
                    $wrapper->set_tpl('{'. $prop_key . '}',  $prop_val);
                    break;
                case 'prev_height':
                    $this->preview_height = $prop_val;
                    $wrapper->set_tpl('{'. $prop_key . '}',  $prop_val);
                    break;
                case 'img_border_width':
                    $this->img_border_width = $prop_val;
                    $wrapper->set_tpl('{'. $prop_key . '}',  $prop_val);
                    break;
                case 'tags_enabled':
                    $this->tags_enabled = $prop_val;
                    $wrapper->set_tpl('{'. $prop_key . '}',  $prop_val);
                    break;
                default:
                    continue;
                    break;
            }
        }


        $album_images = $this->get_album_images ($id);
        // Tags are ready to be entered here.
        if ($this->tags_enabled == '1') {
            $tags = '';
            foreach ($this->tags_array as $key => $value) {
                $tag_id = $value[0];
                $tag_name = $value[1];
                $tag = new template_parser;
                $tag->get_tpl(FILE_PATH . 'templates/tag.tpl');
                $tag->set_tpl('{tag_id}', $tag_id);
                $tag->set_tpl('{tag_name}', $tag_name);
                $tag->tpl_parse();
                $tags = $tags . $tag->template;
                unset($tag);
            }

            $tags_wrapper = new template_parser;
            $tags_wrapper->get_tpl(FILE_PATH . 'templates/tags.tpl');
            $tags_wrapper->set_tpl('{tags}', $tags);
            $tags_wrapper->tpl_parse();

            $wrapper->set_tpl('{TAGS}', $tags_wrapper->template);
            $wrapper->set_tpl('{SEPERATOR_1}', "<div class='creative-gallery-seperator'></div>");
        } else {
            $wrapper->set_tpl('{TAGS}', '');
            $wrapper->set_tpl('{SEPERATOR_1}', "");
        }


        $pages_wrapper = new template_parser;
        switch ($album_info['view_id']) {
            case 1:
            case 2:
            case 3:
            case 7:
            case 8:
            case 9:
            case 10:
                $pages_wrapper->get_tpl(FILE_PATH . 'templates/pages.tpl');
                $pages_wrapper->tpl_parse();
                $wrapper->set_tpl('{PAGES}', $pages_wrapper->template);
                break;
        }



        $images = '';
        foreach ($album_images as $number => $cur_img) {
            $image = new template_parser;

            switch ($album_info['view_id']) {
                case 1:
                case 2:
                case 3:
                case 6:
                    $image->get_tpl(FILE_PATH . 'templates/image.tpl');
                    break;
                case 4:
                case 5:
                    $image->get_tpl(FILE_PATH . 'templates/image1.tpl');
                    break;
                case 7:
                case 8:
                case 9:
                    $image->get_tpl(FILE_PATH . 'templates/image2.tpl');
                    break;
                case 10:
                    $image->get_tpl(FILE_PATH . 'templates/image3.tpl');
                    break;
            }


            $img_path_arr = explode("/", $cur_img['img_path']);
            $img_name = end($img_path_arr);
            $thumbnail_name = 'thumb_' . $album_info['thumbnail_size'] . '_' . $album_info['view_id'] . '_' . $img_name;
            $thumbnail_path = JURI::root(true) . '/media/com_creativegallery/albums/album_' . $id . '/cg_thumbnails/';

            $image->set_tpl('{IMAGE_TAGS}', $cur_img['img_tag']);
            $image->set_tpl('{IMAGE_SRC}', JURI::base(true) . '/components/com_creativegallery/assets/images/dummy.png');
            $image->set_tpl('{IMAGE_ALT}', $cur_img['img_name']);
            $image->set_tpl('{IMAGE_TITLE}', $cur_img['img_title']);
            if (!filter_var($cur_img['img_link'], FILTER_VALIDATE_URL)) {
                $cur_img['img_link'] = "";
            }
            $image->set_tpl('{IMAGE_LINK}', $cur_img['img_link']);
            $image->set_tpl('{IMAGE_TARGET}', $cur_img['img_target']);
            $image->set_tpl('{IMG_DESCRIPTION}', $cur_img['img_description']);
            $image->set_tpl('{IMAGE_PATH}', $cur_img['img_path']);
            $image->set_tpl('{LI_CLASS}', $this->hovers_array_li[$album_info['hover_id']]);
            $image->set_tpl('{IMG_THUMBNAIL}', $thumbnail_path . $thumbnail_name);
            if (filter_var($cur_img['img_path'], FILTER_VALIDATE_URL)) {
                // weblink
                $image->set_tpl('{IMG_CLASS}', 'web_link ' . $this->hovers_array_img[$album_info['hover_id']]);
//                $image->set_tpl('{IMG_THUMBNAIL}', $cur_img['img_path']);
            } else {
                // local
                $image->set_tpl('{IMG_CLASS}', 'local ' . $this->hovers_array_img[$album_info['hover_id']]);
            }

            $image->tpl_parse();
            $images = $images . $image->template;
            unset($image);
        }
        $images_wrapper = new template_parser;

        switch ($album_info['view_id']) {
            case 1:
            case 2:
            case 3:
            case 10:
                $images_wrapper->get_tpl(FILE_PATH . 'templates/grid.tpl');
                $images_wrapper->set_tpl('{IMAGES}', $images);
                $images_wrapper->tpl_parse();
                $wrapper->set_tpl('{CONTENT}', $images_wrapper->template);
                $wrapper->set_tpl('{SEPERATOR_2}', "<div class='creative-gallery-seperator'></div>");
            break;
            case 7:
            case 8:
            case 9:
                $images_wrapper->get_tpl(FILE_PATH . 'templates/grid.tpl');
                $images_wrapper->set_tpl('{IMAGES}', $images);
                $images_wrapper->tpl_parse();
                $wrapper->set_tpl('{CONTENT}', $images_wrapper->template);
                $wrapper->set_tpl('{SEPERATOR_2}', "");
                break;
            case 4:
                $images_wrapper->get_tpl(FILE_PATH . 'templates/carousel.tpl');
                $images_wrapper->set_tpl('{PREVIEW_HEIGHT}', 2*$this->img_border_width + $album_info['thumbnail_size'] . 'px');
                $images_wrapper->set_tpl('{PREVIEW_WIDTH}', '');
                $images_wrapper->set_tpl('{ICON_STYLE_CLASS}', '');
                $images_wrapper->set_tpl('{IMAGES}', $images);
                $images_wrapper->tpl_parse();
                $wrapper->set_tpl('{PAGES}', $images_wrapper->template);

                $preview_wrapper = new template_parser;
                $preview_wrapper->get_tpl(FILE_PATH . 'templates/preview.tpl');
                $preview_wrapper->set_tpl('{START_IMAGE}', JURI::base(true) . '/components/com_creativegallery/assets/images/dummy.png');
                $preview_wrapper->set_tpl('{PREVIEW_HEIGHT}', $this->preview_height . 'px');
                $preview_wrapper->tpl_parse();
                $wrapper->set_tpl('{CONTENT}', $preview_wrapper->template);
                $wrapper->set_tpl('{SEPERATOR_2}', "<div class='creative-gallery-seperator'></div>");
                break;
            case 5:
                $images_wrapper->get_tpl(FILE_PATH . 'templates/carousel.tpl');
                $images_wrapper->set_tpl('{PREVIEW_HEIGHT}', $this->preview_height . 'px');
                $images_wrapper->set_tpl('{IMAGES}', $images);
                $images_wrapper->set_tpl('{ICON_STYLE_CLASS}', 'rotated');
                $images_wrapper->tpl_parse();
                $wrapper->set_tpl('{PAGES}', $images_wrapper->template);

                $preview_wrapper = new template_parser;
                $preview_wrapper->get_tpl(FILE_PATH . 'templates/preview.tpl');
                $preview_wrapper->set_tpl('{START_IMAGE}', JURI::base(true) . '/components/com_creativegallery/assets/images/dummy.png');
                $preview_wrapper->set_tpl('{PREVIEW_HEIGHT}', $this->preview_height . 'px');
                $preview_wrapper->tpl_parse();
                $wrapper->set_tpl('{CONTENT}', $preview_wrapper->template);
                $wrapper->set_tpl('{SEPERATOR_2}', "<div class='creative-gallery-seperator-vertical' style='height: " . $this->preview_height . 'px' .  "'></div>");
                break;
            case 6:
                $images_wrapper->get_tpl(FILE_PATH . 'templates/carousel.tpl');
                $images_wrapper->set_tpl('{PREVIEW_HEIGHT}', 2*$this->img_border_width + $album_info['thumbnail_size'] . 'px');
                $images_wrapper->set_tpl('{PREVIEW_WIDTH}', '100%');
                $images_wrapper->set_tpl('{ICON_STYLE_CLASS}', '');
                $images_wrapper->set_tpl('{IMAGES}', $images);
                $images_wrapper->tpl_parse();
                $wrapper->set_tpl('{CONTENT}', $images_wrapper->template);
                $wrapper->set_tpl('{SEPERATOR_2}', "");
                $wrapper->set_tpl('{PAGES}', '');
                break;
        }
        $wrapper->tpl_parse();
        return $wrapper->template;
    }



    public function render_html()
    {

        return $this->render_album($this->album_id);
        // return "<h1>Creative Gallery Will Be Ready Soon</h1>";
    }

}
