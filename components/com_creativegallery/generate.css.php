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
define('_JEXEC',true);
defined('_JEXEC') or die('Restircted access');
/*
 * This is external PHP file and used on AJAX calls, so it has not "defined('_JEXEC') or die;" part.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_reporting(1);
header('Content-Type: text/css');

//conects to datababse

define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../' ));  
     require_once ( JPATH_BASE .'/includes/defines.php' );
     require_once ( JPATH_BASE .'/includes/framework.php' );
//      $mainframe = JFactory::getApplication('site');

// $id_album = isset($_GET['id_album']) ? (int)$_GET['id_album'] : 0;
$id_album = JFactory::getApplication('site')->input->get('id_album', 0);

$db = JFactory::getDBO();
$query = "SELECT ca.name as album_name, ca.description as album_description, ca.prev_img as album_prev_img, ca.view_id as view_id, 
						 ca.lightbox_id as lightbox_id, ca.hover_id as hover_id, ca.appear_id as appear_id, ca.thumbnail_size as thumbnail_size, 
						 ca.thumbnails_count as thumbnails_count, ca.margin as margin, ca.tmp_styles as tmp_styles, 
						 ca.tmp_tags_hover_styles as tmp_tags_hover_styles,ca.tmp_styles_img_hover as tmp_styles_img_hover FROM `#__cg_albums` as ca
				  WHERE ca.`id` =" . $id_album ;



$db->setQuery($query);
$row = $db->loadAssoc();




$tmp_styles = $row['tmp_styles'];
$tmp_tags_hover_styles = $row['tmp_tags_hover_styles'];
$tmp_styles_img_hover = $row['tmp_styles_img_hover'];

$thumbnail_size = $row['thumbnail_size'];

$tmp_styles_arr = explode("|", $tmp_styles);
$styles_arr = array();
for ($i=0; $i < count($tmp_styles_arr); $i++) { 
	$current = explode(":", $tmp_styles_arr[$i]);
	$styles_arr[$current[0]] = $current[1];
}

$tmp_tags_hover_styles_arr = explode("|", $tmp_tags_hover_styles);

$tags_hover_styles_arr = array();
for ($i=0; $i < count($tmp_tags_hover_styles_arr); $i++) { 
	$current = explode(":", $tmp_tags_hover_styles_arr[$i]);
	$tags_hover_styles_arr[$current[0]] = $current[1];
}

$tmp_styles_img_hover_arr = explode("|", $tmp_styles_img_hover);

$img_hover_styles_arr = array();
for ($i=0; $i < count($tmp_styles_img_hover_arr); $i++) { 
	$current = explode(":", $tmp_styles_img_hover_arr[$i]);
	$img_hover_styles_arr[$current[0]] = $current[1];
}


?>


.creative-gallery-container .creative-gallery-seperator {
	width: 100%;
	height: 0px;
	border-width: 0;
	border-top-width: <?php echo $styles_arr['seperator_width'] . 'px'; ?>;
	border-style : <?php echo $styles_arr['seperator_type']; ?>;
	border-color: <?php echo $styles_arr['seperator_color']; ?>;
	margin: <?php echo $styles_arr['seperator_margin'] . 'px'; ?> 0px;
}

.creative-gallery-container .creative-gallery-seperator-vertical {
	width: 0px;
    border-width: 0;
	border-left-width: <?php echo $styles_arr['seperator_width'] . 'px'; ?>;
	border-style : <?php echo $styles_arr['seperator_type']; ?>;
	border-color: <?php echo $styles_arr['seperator_color']; ?>;
	margin: 0px <?php echo $styles_arr['seperator_margin'] . 'px'; ?>;
    float: left;
}

.creative-gallery-container.creative-gallery-container {
    display: block;
    height: auto;
    background-color: <?php echo $styles_arr['cont_bg_color']; ?>;
    padding: <?php echo $styles_arr['cont_padding'] . 'px'; ?> ;
    border:  <?php echo $styles_arr['cont_border_width'] . 'px ' . $styles_arr['cont_border_type'] . " " . $styles_arr['cont_border_color'] ; ?>;
    border-radius: <?php echo $styles_arr['cont_border_radius'] . 'px'; ?>;
    -webkit-box-shadow: <?php if ($styles_arr['cont_boxsh_type_selector']!="") { echo $styles_arr['cont_boxsh_type_selector'] . " "; } echo $styles_arr['cont_boxsh_h'] . "px " . $styles_arr['cont_boxsh_v'] . "px " . $styles_arr['cont_boxsh_blur'] . "px " . $styles_arr['cont_boxsh_spread'] . "px " . $styles_arr['cont_boxsh_color'] ; ?>;
    -moz-box-shadow: <?php if ($styles_arr['cont_boxsh_type_selector']!="") { echo $styles_arr['cont_boxsh_type_selector'] . " "; } echo $styles_arr['cont_boxsh_h'] . "px " . $styles_arr['cont_boxsh_v'] . "px " . $styles_arr['cont_boxsh_blur'] . "px " . $styles_arr['cont_boxsh_spread'] . "px " . $styles_arr['cont_boxsh_color'] ; ?>;
    -ms-box-shadow: <?php if ($styles_arr['cont_boxsh_type_selector']!="") { echo $styles_arr['cont_boxsh_type_selector'] . " "; } echo $styles_arr['cont_boxsh_h'] . "px " . $styles_arr['cont_boxsh_v'] . "px " . $styles_arr['cont_boxsh_blur'] . "px " . $styles_arr['cont_boxsh_spread'] . "px " . $styles_arr['cont_boxsh_color'] ; ?>;
    -o-box-shadow: <?php if ($styles_arr['cont_boxsh_type_selector']!="") { echo $styles_arr['cont_boxsh_type_selector'] . " "; } echo $styles_arr['cont_boxsh_h'] . "px " . $styles_arr['cont_boxsh_v'] . "px " . $styles_arr['cont_boxsh_blur'] . "px " . $styles_arr['cont_boxsh_spread'] . "px " . $styles_arr['cont_boxsh_color'] ; ?>;
    box-shadow: <?php if ($styles_arr['cont_boxsh_type_selector']!="") { echo $styles_arr['cont_boxsh_type_selector'] . " "; } echo $styles_arr['cont_boxsh_h'] . "px " . $styles_arr['cont_boxsh_v'] . "px " . $styles_arr['cont_boxsh_blur'] . "px " . $styles_arr['cont_boxsh_spread'] . "px " . $styles_arr['cont_boxsh_color'] ; ?>;
}

.creative-gallery-container .creative-gallery-image-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
    height: 100%;
    /*border: <?php echo $styles_arr['img_border_width'] . 'px ' . $styles_arr['img_border_type'] . " " . $styles_arr['img_border_color'] ; ?>;*/
    border-radius: <?php echo $styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
    -webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -webkit-mask: URL("<?php echo JURI::base(true) . '/assets/images/dummy.png' ?>") ;
}

.creative-gallery-container .creative-gallery-image-wrapper img {
    border-radius: <?php echo $styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
    -webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
}

.creative-gallery-container ul.gallery {
    -webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
}

.creative-gallery-container li.visible {
    -webkit-box-shadow: <?php if ($styles_arr['img_boxsh_type']!="") { echo $styles_arr['img_boxsh_type'] . " "; } echo $styles_arr['img_boxsh_h'] . "px " . $styles_arr['img_boxsh_v'] . "px " . $styles_arr['img_boxsh_blur'] . "px " . $styles_arr['img_boxsh_spread'] . "px " . $styles_arr['img_boxsh_color'] ; ?>;
    -moz-box-shadow: <?php if ($styles_arr['img_boxsh_type']!="") { echo $styles_arr['img_boxsh_type'] . " "; } echo $styles_arr['img_boxsh_h'] . "px " . $styles_arr['img_boxsh_v'] . "px " . $styles_arr['img_boxsh_blur'] . "px " . $styles_arr['img_boxsh_spread'] . "px " . $styles_arr['img_boxsh_color'] ; ?>;
    -ms-box-shadow: <?php if ($styles_arr['img_boxsh_type']!="") { echo $styles_arr['img_boxsh_type'] . " "; } echo $styles_arr['img_boxsh_h'] . "px " . $styles_arr['img_boxsh_v'] . "px " . $styles_arr['img_boxsh_blur'] . "px " . $styles_arr['img_boxsh_spread'] . "px " . $styles_arr['img_boxsh_color'] ; ?>;
    -o-box-shadow: <?php if ($styles_arr['img_boxsh_type']!="") { echo $styles_arr['img_boxsh_type'] . " "; } echo $styles_arr['img_boxsh_h'] . "px " . $styles_arr['img_boxsh_v'] . "px " . $styles_arr['img_boxsh_blur'] . "px " . $styles_arr['img_boxsh_spread'] . "px " . $styles_arr['img_boxsh_color'] ; ?>;
    box-shadow: <?php if ($styles_arr['img_boxsh_type']!="") { echo $styles_arr['img_boxsh_type'] . " "; } echo $styles_arr['img_boxsh_h'] . "px " . $styles_arr['img_boxsh_v'] . "px " . $styles_arr['img_boxsh_blur'] . "px " . $styles_arr['img_boxsh_spread'] . "px " . $styles_arr['img_boxsh_color'] ; ?>;
	border: <?php echo $styles_arr['img_border_width'] . 'px ' . $styles_arr['img_border_type'] . " " . $styles_arr['img_border_color'] ; ?>;
	border-radius: <?php echo $styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
	-webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
}

.creative-gallery-container li.visible:hover {
    border-radius: <?php echo $img_hover_styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
    border: <?php echo $styles_arr['img_border_width'] . 'px ' . $styles_arr['img_border_type'] . " " . $img_hover_styles_arr['img_border_color'] ; ?>;
    -webkit-box-shadow: <?php if ($img_hover_styles_arr['img_boxsh_type']!="") { echo $img_hover_styles_arr['img_boxsh_type'] . " "; } echo $img_hover_styles_arr['img_boxsh_h'] . "px " . $img_hover_styles_arr['img_boxsh_v'] . "px " . $img_hover_styles_arr['img_boxsh_blur'] . "px " . $img_hover_styles_arr['img_boxsh_spread'] . "px " . $img_hover_styles_arr['img_boxsh_color'] ; ?>;
    -moz-box-shadow: <?php if ($img_hover_styles_arr['img_boxsh_type']!="") { echo $img_hover_styles_arr['img_boxsh_type'] . " "; } echo $img_hover_styles_arr['img_boxsh_h'] . "px " . $img_hover_styles_arr['img_boxsh_v'] . "px " . $img_hover_styles_arr['img_boxsh_blur'] . "px " . $img_hover_styles_arr['img_boxsh_spread'] . "px " . $img_hover_styles_arr['img_boxsh_color'] ; ?>;
    -ms-box-shadow: <?php if ($img_hover_styles_arr['img_boxsh_type']!="") { echo $img_hover_styles_arr['img_boxsh_type'] . " "; } echo $img_hover_styles_arr['img_boxsh_h'] . "px " . $img_hover_styles_arr['img_boxsh_v'] . "px " . $img_hover_styles_arr['img_boxsh_blur'] . "px " . $img_hover_styles_arr['img_boxsh_spread'] . "px " . $img_hover_styles_arr['img_boxsh_color'] ; ?>;
    -o-box-shadow: <?php if ($img_hover_styles_arr['img_boxsh_type']!="") { echo $img_hover_styles_arr['img_boxsh_type'] . " "; } echo $img_hover_styles_arr['img_boxsh_h'] . "px " . $img_hover_styles_arr['img_boxsh_v'] . "px " . $img_hover_styles_arr['img_boxsh_blur'] . "px " . $img_hover_styles_arr['img_boxsh_spread'] . "px " . $img_hover_styles_arr['img_boxsh_color'] ; ?>;
    box-shadow: <?php if ($img_hover_styles_arr['img_boxsh_type']!="") { echo $img_hover_styles_arr['img_boxsh_type'] . " "; } echo $img_hover_styles_arr['img_boxsh_h'] . "px " . $img_hover_styles_arr['img_boxsh_v'] . "px " . $img_hover_styles_arr['img_boxsh_blur'] . "px " . $img_hover_styles_arr['img_boxsh_spread'] . "px " . $img_hover_styles_arr['img_boxsh_color'] ; ?>;    
}

.creative-gallery-container li.visible.current {
	border-radius: <?php echo $img_hover_styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
	border-color: <?php echo $img_hover_styles_arr['img_border_color']; ?>;
}



.creative-gallery-container .creative-gallery-image-wrapper .overlay {
	position: absolute;
	top: 0;
	left: 0;
    width: 100%;
    height: 100%;
    display: none;
}

.creative-gallery-container li.visible:hover .creative-gallery-image-wrapper {
	border-radius: <?php echo $img_hover_styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
	border-color: <?php echo $img_hover_styles_arr['img_border_color']; ?>;
}


.creative-gallery-container li.visible:hover img {
    border-radius: <?php echo $img_hover_styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
}

.creative-gallery-container .creative-gallery-image-main-wrapper {
	border-radius: <?php echo $styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
    overflow: hidden;
    -webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0px;
    left: 0px;
}

.creative-gallery-container li.visible:hover .creative-gallery-image-main-wrapper {
	border-radius: <?php echo $img_hover_styles_arr['img_border_radius'] . $styles_arr['img_border_radius_type']; ?> ;
	
}

.creative-gallery-container .creative-gallery-text {
	display : none;
	-webkit-text-shadow:  <?php echo $styles_arr['txtsh_h'] . "px " . $styles_arr['txtsh_v'] . "px " . $styles_arr['txtsh_blur'] . "px " . $styles_arr['txtsh_color'] ; ?>;
	-moz-text-shadow:  <?php echo $styles_arr['txtsh_h'] . "px " . $styles_arr['txtsh_v'] . "px " . $styles_arr['txtsh_blur'] . "px " . $styles_arr['txtsh_color'] ; ?>;
	-ms-text-shadow:  <?php echo $styles_arr['txtsh_h'] . "px " . $styles_arr['txtsh_v'] . "px " . $styles_arr['txtsh_blur'] . "px " . $styles_arr['txtsh_color'] ; ?>;
	-o-text-shadow:  <?php echo $styles_arr['txtsh_h'] . "px " . $styles_arr['txtsh_v'] . "px " . $styles_arr['txtsh_blur'] . "px " . $styles_arr['txtsh_color'] ; ?>;
	text-shadow:  <?php echo $styles_arr['txtsh_h'] . "px " . $styles_arr['txtsh_v'] . "px " . $styles_arr['txtsh_blur'] . "px " . $styles_arr['txtsh_color'] ; ?>;
    letter-spacing: <?php echo $styles_arr['txt_letter_spacing'] . 'px'; ?>;
    word-spacing: <?php echo $styles_arr['txt_word_spacing'] . 'px'; ?>;
    line-height: <?php echo $styles_arr['txt_line_height'] . '%'; ?>;
    color:  <?php echo $styles_arr['txt_color']; ?>;
    font-size: <?php echo $styles_arr['txt_size'] . 'px'; ?>;
    direction:  <?php echo $styles_arr['txt_direction']; ?>;
    unicode-bidi: <?php echo $styles_arr['txt_unicode_bibi']; ?>;
    text-decoration: <?php echo $styles_arr['txt_decoration']; ?>;
    text-transform: <?php echo $styles_arr['txt_transform']; ?>;
}


<?php
//    echo '<pre>';
//    print_r($styles_arr);
//    echo '</pre>';
?>



/*Icon Styles*/

.creative-gallery-container .creative-gallery-icon {
    display: block;
    position: absolute;
	cursor: pointer;
	background-repeat: no-repeat;
    background-position: center center;
	width: <?php echo $styles_arr['img_icon_width'] . 'px'; ?>;
    height: <?php echo $styles_arr['img_icon_width'] . 'px'; ?>;
    -webkit-box-shadow: <?php if ($styles_arr['icons_boxsh_type']!="") { echo $styles_arr['icons_boxsh_type'] . " "; } echo $styles_arr['icons_boxsh_h'] . "px " . $styles_arr['icons_boxsh_v'] . "px " . $styles_arr['icons_boxsh_blur'] . "px " . $styles_arr['icons_boxsh_spread'] . "px " . $styles_arr['icons_boxsh_color'] ; ?> ;
    -moz-box-shadow: <?php if ($styles_arr['icons_boxsh_type']!="") { echo $styles_arr['icons_boxsh_type'] . " "; } echo $styles_arr['icons_boxsh_h'] . "px " . $styles_arr['icons_boxsh_v'] . "px " . $styles_arr['icons_boxsh_blur'] . "px " . $styles_arr['icons_boxsh_spread'] . "px " . $styles_arr['icons_boxsh_color'] ; ?> ;
    -ms-box-shadow: <?php if ($styles_arr['icons_boxsh_type']!="") { echo $styles_arr['icons_boxsh_type'] . " "; } echo $styles_arr['icons_boxsh_h'] . "px " . $styles_arr['icons_boxsh_v'] . "px " . $styles_arr['icons_boxsh_blur'] . "px " . $styles_arr['icons_boxsh_spread'] . "px " . $styles_arr['icons_boxsh_color'] ; ?> ;
    -o-box-shadow: <?php if ($styles_arr['icons_boxsh_type']!="") { echo $styles_arr['icons_boxsh_type'] . " "; } echo $styles_arr['icons_boxsh_h'] . "px " . $styles_arr['icons_boxsh_v'] . "px " . $styles_arr['icons_boxsh_blur'] . "px " . $styles_arr['icons_boxsh_spread'] . "px " . $styles_arr['icons_boxsh_color'] ; ?> ;
    box-shadow: <?php if ($styles_arr['icons_boxsh_type']!="") { echo $styles_arr['icons_boxsh_type'] . " "; } echo $styles_arr['icons_boxsh_h'] . "px " . $styles_arr['icons_boxsh_v'] . "px " . $styles_arr['icons_boxsh_blur'] . "px " . $styles_arr['icons_boxsh_spread'] . "px " . $styles_arr['icons_boxsh_color'] ; ?> ;
    border: <?php echo $styles_arr['img_icon_border_w'] . 'px ' . $styles_arr['img_icon_border_t'] . " " . $styles_arr['img_icon_border_color'] ; ?>; 
    border-radius: <?php echo $styles_arr['img_icon_border_r'] . '%'; ?>;
    background-color: <?php echo $styles_arr['img_icon_color']; ?>;
    background-size: <?php echo $styles_arr['img_icon_prop'] . '%'; ?>;
    -webkit-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -moz-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -ms-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    -o-transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
    transition: <?php echo "all " . (int)$img_hover_styles_arr['img_anim_speed']/1000 . 's'; ?>;
}

.creative-gallery-container .creative-gallery-image-wrapper .creative-gallery-icon:hover {
    -webkit-transform: rotate(20deg) scale(1.2)!important;
    -ms-transform: rotate(20deg) scale(1.2)!important;;
    -o-transform: rotate(20deg) scale(1.2)!important;;
    transform: rotate(20deg) scale(1.2)!important;;
}
<?php
    $rotateX = "-webkit-transform: rotateX(90deg); -ms-transform: rotateX(90deg); -o-transform: rotateX(90deg); transform: rotateX(90deg);";
    $rotateY = "-webkit-transform: rotateY(90deg); -ms-transform: rotateY(90deg); -o-transform: rotateY(90deg); transform: rotateY(90deg);";
    $rotateY_0 = "-webkit-transform: rotateY(0deg); -ms-transform: rotateY(0deg); -o-transform: rotateY(0deg); transform: rotateY(0deg);";
    $rotateX_0 = "-webkit-transform: rotateX(0deg); -ms-transform: rotateX(0deg); -o-transform: rotateX(0deg); transform: rotateX(0deg);";
?>

.creative-gallery-container .creative-gallery-icon-zoom {
	<?php if (($styles_arr['img_icon_type']=="link_only")||($styles_arr['img_icon_type']=="none")) { echo "display:none!important;" ; } ?>
	background-image: URL("<?php echo JURI::base(true).'/assets/images/image_icons/' . $styles_arr['img_zoom_template'] . '.png' ?>");
    top: <?php
        switch ($styles_arr['img_icon_effect']) {
            case 1:
            case 4:
            case 5:
                echo ((int)$styles_arr['img_icon_top1'] - (int)$styles_arr['img_icon_width']/2). 'px';
                break;
            case 2:
            case 3:
                echo ((int)$styles_arr['img_icon_top1'] - (int)$styles_arr['img_icon_width']/2) - (int)$thumbnail_size . 'px';
                break;
        }
    ?>;
    left: <?php
        switch ($styles_arr['img_icon_effect']) {
            case 2:
            case 4:
            case 5:
                echo ((int)$styles_arr['img_icon_left1'] - (int)$styles_arr['img_icon_width']/2) . 'px';
                break;
            case 1:
            case 3:
                echo ((int)$styles_arr['img_icon_left1'] - (int)$styles_arr['img_icon_width']/2) - (int)$thumbnail_size. 'px';
                break;
        }
    ?>;
    <?php
        switch ($styles_arr['img_icon_effect']) {
            case 4:
                echo $rotateY;
                break;
            case 5:
                echo $rotateX;
                break;
            case 1:
            case 2:
            case 3:
            break;
        }
    ?>;

}

.creative-gallery-container .creative-gallery-icon-link {
	<?php if (($styles_arr['img_icon_type']=="zoom_only")||($styles_arr['img_icon_type']=="none")) { echo "display:none!important;" ; } ?>
	background-image: URL("<?php echo JURI::base(true).'/assets/images/image_icons/' . $styles_arr['img_link_template'] . '.png' ?>");
    top: <?php
        switch ($styles_arr['img_icon_effect']) {
            case 1:
            case 4:
            case 5:
                echo ((int)$styles_arr['img_icon_top2'] - (int)$styles_arr['img_icon_width']/2). 'px';
                break;
            case 2:
            case 3:
                echo ((int)$styles_arr['img_icon_top2'] - (int)$styles_arr['img_icon_width']/2) + (int)$thumbnail_size. 'px';
                break;
        }
    ?>;
    left: <?php
        switch ($styles_arr['img_icon_effect']) {
            case 4:
            case 5:
            case 2:
                echo ((int)$styles_arr['img_icon_left2'] - (int)$styles_arr['img_icon_width']/2). 'px';
                break;
            case 1:
            case 3:
                echo ((int)$styles_arr['img_icon_left2'] - (int)$styles_arr['img_icon_width']/2) + (int)$thumbnail_size . 'px';
                break;
        }
    ?>;
    <?php
       switch ($styles_arr['img_icon_effect']) {
           case 4:
               echo $rotateY;
               break;
           case 5:
               echo $rotateX;
               break;
           case 1:
           case 2:
           case 3:
           break;
       }
    ?>;



}

.creative-gallery-container .creative-gallery-image-wrapper:hover .creative-gallery-icon-zoom {
    top:  <?php echo ((int)$styles_arr['img_icon_top1'] - (int)$styles_arr['img_icon_width']/2) . 'px'; ?>;
    left: <?php echo ((int)$styles_arr['img_icon_left1'] - (int)$styles_arr['img_icon_width']/2) . 'px'; ?>;
    <?php
        switch ($styles_arr['img_icon_effect']) {
            case 4:
                echo $rotateY_0;
                break;
            case 5:
                echo $rotateX_0;
                break;
            case 1:
            case 2:
            case 3:
            break;
        }
    ?>;
}

.creative-gallery-container .creative-gallery-image-wrapper:hover .creative-gallery-icon-link {
    top:  <?php echo ((int)$styles_arr['img_icon_top2'] - (int)$styles_arr['img_icon_width']/2) . 'px'; ?>;
    left: <?php echo ((int)$styles_arr['img_icon_left2'] - (int)$styles_arr['img_icon_width']/2) . 'px'; ?>;
    <?php
        switch ($styles_arr['img_icon_effect']) {
            case 4:
                echo $rotateY_0;
                break;
            case 5:
                echo $rotateX_0;
                break;
            case 1:
            case 2:
            case 3:
            break;
        }
    ?>;
}

/*Tags Styles*/

.creative-gallery-container .creative-gallery-tag {
	display : inline-block;
	cursor : pointer;
	margin: 0 <?php echo $styles_arr['tags_margin'] . 'px'; ?> 0;
	padding: <?php echo $styles_arr['tags_padding_h'] . 'px ' . $styles_arr['tags_padding_v'] . 'px'; ?> ;
	background-color: <?php echo $styles_arr['tags_bg_color']; ?>;
	border: <?php echo $styles_arr['tags_border_width'] . 'px ' . $styles_arr['tags_border_type'] . " " . $styles_arr['tags_border_color'] ; ?>; 
	border-radius: <?php echo $styles_arr['tags_border_radius'] . 'px'; ?>;
	-webkit-box-shadow: <?php if ($styles_arr['tags_boxsh_type']!="") { echo $styles_arr['tags_boxsh_type'] . " "; } echo $styles_arr['tags_boxsh_h'] . "px " . $styles_arr['tags_boxsh_v'] . "px " . $styles_arr['tags_boxsh_blur'] . "px " . $styles_arr['tags_boxsh_spread'] . "px " . $styles_arr['tags_boxsh_color'] ; ?> ;
	-moz-box-shadow: <?php if ($styles_arr['tags_boxsh_type']!="") { echo $styles_arr['tags_boxsh_type'] . " "; } echo $styles_arr['tags_boxsh_h'] . "px " . $styles_arr['tags_boxsh_v'] . "px " . $styles_arr['tags_boxsh_blur'] . "px " . $styles_arr['tags_boxsh_spread'] . "px " . $styles_arr['tags_boxsh_color'] ; ?> ;
	-ms-box-shadow: <?php if ($styles_arr['tags_boxsh_type']!="") { echo $styles_arr['tags_boxsh_type'] . " "; } echo $styles_arr['tags_boxsh_h'] . "px " . $styles_arr['tags_boxsh_v'] . "px " . $styles_arr['tags_boxsh_blur'] . "px " . $styles_arr['tags_boxsh_spread'] . "px " . $styles_arr['tags_boxsh_color'] ; ?> ;
	-o-box-shadow: <?php if ($styles_arr['tags_boxsh_type']!="") { echo $styles_arr['tags_boxsh_type'] . " "; } echo $styles_arr['tags_boxsh_h'] . "px " . $styles_arr['tags_boxsh_v'] . "px " . $styles_arr['tags_boxsh_blur'] . "px " . $styles_arr['tags_boxsh_spread'] . "px " . $styles_arr['tags_boxsh_color'] ; ?> ;
	box-shadow: <?php if ($styles_arr['tags_boxsh_type']!="") { echo $styles_arr['tags_boxsh_type'] . " "; } echo $styles_arr['tags_boxsh_h'] . "px " . $styles_arr['tags_boxsh_v'] . "px " . $styles_arr['tags_boxsh_blur'] . "px " . $styles_arr['tags_boxsh_spread'] . "px " . $styles_arr['tags_boxsh_color'] ; ?> ;
	-webkit-text-shadow:  <?php echo $styles_arr['tags_txtsh_h'] . "px " . $styles_arr['tags_txtsh_v'] . "px " . $styles_arr['tags_txtsh_blur'] . "px " . $styles_arr['tags_txtsh_color'] ; ?>;
	-moz-text-shadow:  <?php echo $styles_arr['tags_txtsh_h'] . "px " . $styles_arr['tags_txtsh_v'] . "px " . $styles_arr['tags_txtsh_blur'] . "px " . $styles_arr['tags_txtsh_color'] ; ?>;
	-ms-text-shadow:  <?php echo $styles_arr['tags_txtsh_h'] . "px " . $styles_arr['tags_txtsh_v'] . "px " . $styles_arr['tags_txtsh_blur'] . "px " . $styles_arr['tags_txtsh_color'] ; ?>;
	-o-text-shadow:  <?php echo $styles_arr['tags_txtsh_h'] . "px " . $styles_arr['tags_txtsh_v'] . "px " . $styles_arr['tags_txtsh_blur'] . "px " . $styles_arr['tags_txtsh_color'] ; ?>;
	text-shadow:  <?php echo $styles_arr['tags_txtsh_h'] . "px " . $styles_arr['tags_txtsh_v'] . "px " . $styles_arr['tags_txtsh_blur'] . "px " . $styles_arr['tags_txtsh_color'] ; ?>;
	letter-spacing: <?php echo $styles_arr['tags_txt_letter_spacing'] . 'px'; ?>;
	word-spacing: <?php echo $styles_arr['tags_txt_word_spacing'] . 'px'; ?>;
	color:  <?php echo $styles_arr['tags_txt_color']; ?>;
	line-height: <?php echo $styles_arr['tags_txt_line_height'] . '%'; ?>;
	font-size: <?php echo $styles_arr['tags_txt_size'] . 'px'; ?>;
	direction:  <?php echo $styles_arr['tags_txt_direction']; ?>;
	unicode-bidi: <?php echo $styles_arr['tags_txt_unicode_bibi']; ?>;
	text-decoration: <?php echo $styles_arr['tags_txt_decoration']; ?>;
	text-transform: <?php echo $styles_arr['tags_txt_transform']; ?>;
	transition: <?php echo "all " . (int)$tags_hover_styles_arr['tags_anim_speed']/1000 . 's'; ?>;
	-webkit-transition: <?php echo "all " . (int)$tags_hover_styles_arr['tags_anim_speed']/1000 . 's'; ?>;
	-moz-transition: <?php echo "all " . (int)$tags_hover_styles_arr['tags_anim_speed']/1000 . 's'; ?>;
	-ms-transition: <?php echo "all " . (int)$tags_hover_styles_arr['tags_anim_speed']/1000 . 's'; ?>;
	-o-transition: <?php echo "all " . (int)$tags_hover_styles_arr['tags_anim_speed']/1000 . 's'; ?>;
}

/*Tags Styles Hover*/

.creative-gallery-container .creative-gallery-tag:hover {
    background-color: <?php echo $tags_hover_styles_arr['tags_bg_color']; ?>;
    border: <?php echo $styles_arr['tags_border_width'] . 'px ' . $tags_hover_styles_arr['tags_border_type'] . " " . $tags_hover_styles_arr['tags_border_color'] ; ?>;
    border-radius: <?php echo $tags_hover_styles_arr['tags_border_radius'] . 'px'; ?>;
    
    -webkit-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -moz-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -ms-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -o-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    
    -webkit-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -moz-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -ms-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -o-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    
    color:  <?php echo $tags_hover_styles_arr['tags_txt_color']; ?>;
    text-decoration: <?php echo $tags_hover_styles_arr['tags_txt_decoration']; ?>;
    text-transform: <?php echo $tags_hover_styles_arr['tags_txt_transform']; ?>;
}

.creative-gallery-container .creative-gallery-tag.selected {
    background-color: <?php echo $tags_hover_styles_arr['tags_bg_color']; ?>;
    border: <?php echo $styles_arr['tags_border_width'] . 'px ' . $tags_hover_styles_arr['tags_border_type'] . " " . $tags_hover_styles_arr['tags_border_color'] ; ?>;
    border-radius: <?php echo $tags_hover_styles_arr['tags_border_radius'] . 'px'; ?>;
    
    -webkit-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -moz-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -ms-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    -o-box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    box-shadow: <?php if ($tags_hover_styles_arr['tags_boxsh_type']!="") { echo $tags_hover_styles_arr['tags_boxsh_type'] . " "; } echo $tags_hover_styles_arr['tags_boxsh_h'] . "px " . $tags_hover_styles_arr['tags_boxsh_v'] . "px " . $tags_hover_styles_arr['tags_boxsh_blur'] . "px " . $tags_hover_styles_arr['tags_boxsh_spread'] . "px " . $tags_hover_styles_arr['tags_boxsh_color'] ; ?> ;
    
    -webkit-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -moz-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -ms-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    -o-text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    text-shadow:  <?php echo $tags_hover_styles_arr['tags_txtsh_h'] . "px " . $tags_hover_styles_arr['tags_txtsh_v'] . "px " . $tags_hover_styles_arr['tags_txtsh_blur'] . "px " . $tags_hover_styles_arr['tags_txtsh_color'] ; ?>;
    
    color:  <?php echo $tags_hover_styles_arr['tags_txt_color']; ?>;
    text-decoration: <?php echo $tags_hover_styles_arr['tags_txt_decoration']; ?>;
    text-transform: <?php echo $tags_hover_styles_arr['tags_txt_transform']; ?>;
}

