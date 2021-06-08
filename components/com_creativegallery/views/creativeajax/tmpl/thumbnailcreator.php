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
defined('_JEXEC') or die('Restircted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');


// Development Oprions /////
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
////////////////////////////

class thumbnail_creator
{
    var $mode_map = array(
        1 => "square",
        2 => "portrait",
        3 => "landscape",
        4 => "landscape",
        5 => "portrait",
        6 => "square",
        7 => "square",
        8 => "square",
        9 => "square",
        10 => "square",
        11 => "square",
        12 => "square",
        13 => "square"
    );
    var $size;
    var $mode;
    var $view_id;
    var $img_name;
    var $album_id;
    var $output_path;
    var $img_type;
    var $current_file;
    var $output_file;

    var $image_width;
    var $image_height;

    var $image_cut_width;
    var $image_cut_height;

    var $image_extension;

    var $image;
    var $thumbnail;


    var $thumbnail_width;
    var $thumbnail_height;
    var $start_x;
    var $start_y;

    var $errors = array();

    public function __construct()
    {
        if (isset($_REQUEST['img_name'])) {
            $this->img_name = $_REQUEST['img_name'];
        } else {
            array_push($this->errors, 'img_name parameter is not valid');
        }
        if (isset($_REQUEST['album_id'])) {
            $this->album_id = $_REQUEST['album_id'];
        } else {
            array_push($this->errors, 'album_id parameter is not valid');
        }
        if (isset($_REQUEST['img_type'])) {
            $this->img_type = $_REQUEST['img_type'];
        } else {
            array_push($this->errors, 'img_type parameter is not valid');
        }
        if (count($this->errors) == 0 ) {
            $this->get_album_info();
        }
    }

    public function get_album_info ()
    {
        $req = new JObject();
        $query = "SELECT `view_id`, `thumbnail_size` FROM `#__cg_albums` WHERE `id` = " . $this->album_id;

        $db = JFactory::getDBO();
        $db->setQuery($query);
        $list = $db->loadAssoc();

        $this->size = $list['thumbnail_size'];
        $this->view_id = $list['view_id'];
        $this->mode = $this->mode_map[$this->view_id];
    }
    public function filter_paths($images)
    {
        $arr = array();

        foreach ($images as $key => $image) {
            if (filter_var($image['path'], FILTER_VALIDATE_URL)==false) {
                $arr[] = $image['path'];
            }
        }
        return $arr;
    }
    public function create_output_path() {
        $this->output_path = $_SERVER["DOCUMENT_ROOT"] . JURI::root(true) . '/media/com_creativegallery/';

        if (!is_dir($this->output_path)) {
            if (!mkdir ($this->output_path)) {
                array_push($this->errors, 'unable to create directory ' . $this->output_path);
            }
        }

        $this->output_path = $this->output_path  . 'albums';

        if (!is_dir($this->output_path)) {
            if (!mkdir ($this->output_path)) {
                array_push($this->errors, 'unable to create directory ' . $this->output_path);
            }
        }

        $this->output_path = $this->output_path  . '/album_' . $this->album_id;
        if (!is_dir($this->output_path)) {
            if (!mkdir ($this->output_path)) {
                array_push($this->errors, 'unable to create directory ' . $this->output_path);
            }
        }

        $this->output_path = $this->output_path . '/cg_thumbnails';
        if (!is_dir($this->output_path)) {
            if (!mkdir ($this->output_path)) {
                array_push($this->errors, 'unable to create directory ' . $this->output_path);
            }
        }
    }

    public function create_image() {
        $this->image_extension = exif_imagetype($this->current_file);
        if ($this->image_extension=="3") {
            $this->image = imagecreatefrompng ($this->current_file);
        } else if ($this->image_extension=="2") {
            $this->image = imagecreatefromjpeg($this->current_file);
        } else if ($this->image_extension=="1") {
            $this->image = imagecreatefromgif ($this->current_file);
        }
    }
    public function create_thumbnail () {
        $this->create_output_path();
        if ($this->img_type === 'local') {
            $this->current_file = $_SERVER["DOCUMENT_ROOT"] . $this->img_name;
        } else {
            $this->current_file = $this->img_name;
        }
        $arr = explode('/', $this->img_name);
        $cur_img_name = end($arr);

        $img_thumb_name = 'thumb_' . $this->size . '_' . $this->view_id . '_' . $cur_img_name;

        $this->output_file = $this->output_path . '/' . $img_thumb_name;

        if (is_file($this->output_file)) {
            return 'done';
        }

        if (!is_file($this->current_file)) {
            array_push($this->errors, 'unable to find file ' . $this->current_file);
        }

        $this->create_image();

        list( $this->image_width, $this->image_height) = getimagesize($this->current_file);

        if ($this->mode == 'square') {
            # Creating Square Thumbnail (cropping)
            $this->thumbnail_width = $this->size;
            $this->thumbnail_height = $this->size;
            if ($this->image_height >= $this->image_width ) {
                $this->image_cut_width = $this->image_width;
                $this->image_cut_height = $this->image_width;
                $this->start_y = ($this->image_height - $this->image_cut_height)/2;
                $this->start_x = 0;
            } else {
                $this->image_cut_width = $this->image_height;
                $this->image_cut_height = $this->image_height;
                $this->start_x = ($this->image_width - $this->image_cut_width)/2;
                $this->start_y = 0;
            }
        } elseif ($this->mode == 'portrait') {
            $this->thumbnail_width = $this->size;
            $this->thumbnail_height = $this->size + $this->size*rand(0, 8)/20;
            $thumb_ratio = $this->thumbnail_height/$this->thumbnail_width;
            $image_ratio = $this->image_height/$this->image_width;

            if ($image_ratio<1) {
                $this->image_cut_height = $this->image_height;
                $this->image_cut_width = $this->image_height/$thumb_ratio;
                $this->start_y = 0;
                $this->start_x = ($this->image_width - $this->image_cut_width)/2;
            } else {
                if ($thumb_ratio > $image_ratio) {
                    $this->image_cut_height = $this->image_height;
                    $this->image_cut_width = $this->image_height/$thumb_ratio;
                    $this->start_y = 0;
                    $this->start_x = ($this->image_width - $this->image_cut_width)/2;
                } else {
                    $this->image_cut_width = $this->image_width;
                    $this->image_cut_height = $this->image_width*$thumb_ratio;
                    $this->start_y = ($this->image_height - $this->image_cut_height)/2;
                    $this->start_x = 0;
                }
            }
        } elseif ($this->mode == 'landscape') {

            $this->thumbnail_height = $this->size;
            $this->thumbnail_width = $this->size + $this->size*rand(0, 8)/20;

            $thumb_ratio = $this->thumbnail_height/$this->thumbnail_width;
            $image_ratio = $this->image_height/$this->image_width;

            if ($image_ratio>1) {
                $this->image_cut_width = $this->image_width;
                $this->image_cut_height = $this->image_width*$thumb_ratio;
                $this->start_x = 0;
                $this->start_y = ($this->image_height - $this->image_cut_height)/2;
            } else {
                if ($thumb_ratio > $image_ratio) {
                    $this->image_cut_height = $this->image_height;
                    $this->image_cut_width = $this->image_height/$thumb_ratio;
                    $this->start_y = 0;
                    $this->start_x = ($this->image_width - $this->image_cut_width)/2;
                } else {
                    $this->image_cut_width = $this->image_width;
                    $this->image_cut_height = $this->image_width*$thumb_ratio;
                    $this->start_y = ($this->image_height - $this->image_cut_height)/2;
                    $this->start_x = 0;
                }
            }
        }
        $this->thumbnail = imagecreatetruecolor($this->thumbnail_width, $this->thumbnail_height);
        $this->create();
        return 'done';
    }

    public function create() {
        imagecopyresampled(
            $this->thumbnail,
            $this->image,
            0,
            0,
            $this->start_x,
            $this->start_y,
            $this->thumbnail_width,
            $this->thumbnail_height,
            $this->image_cut_width,
            $this->image_cut_height);
        if ($this->image_extension=="3") {
            imagepng($this->thumbnail, $this->output_file, 9);
        } else if ($this->image_extension=="2") {
            imagejpeg($this->thumbnail, $this->output_file, 100);
        } else if ($this->image_extension=="1") {
            imagegif($this->thumbnail, $this->output_file, 100);
        }
    }

    public function get_result() {
        $result = $this->create_thumbnail();
        if (count($this->errors) == 0) {
            return json_encode($result);
        } else {
            return json_encode($this->errors);
        }
    }
}

$creator = new thumbnail_creator;

$response = $creator->get_result();

echo $response;

?>