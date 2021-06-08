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

//JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');
?>
<?php 
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');
// JHtml::_('formbehavior.chosen', 'select');

$document = JFactory::getDocument();
//adding stylesheets
$document->addStyleSheet("components/com_creativegallery/assets/css/reset.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/filemanager/filemanager.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/filemanager/fileuploader.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/filemanager/tagmanager.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/filemanager/loader.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/hover.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/table.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/add_album.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/add_image_popup.css");

//adding jQuery

$document->addScript(JURI::root(true) . "/components/com_creativegallery/assets/js/libs/jquery-2.2.3.min.js");
$document->addScript("components/com_creativegallery/assets/js/jquery/jquery-ui.js");

//adding JqueryUI stylesheets


$document->addStyleSheet("components/com_creativegallery/assets/css/jquery/jquery-ui.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/jquery/jquery-ui.structure.css");
$document->addStyleSheet("components/com_creativegallery/assets/css/jquery/jquery-ui.theme.css");

// adding colorpicker

$document->addStyleSheet("components/com_creativegallery/assets/css/colorpicker/colorpicker.css");
$document->addScript("components/com_creativegallery/assets/js/colorpicker/colorpicker.js");

// adding table reorder script

//$document->addScript("components/com_creativegallery/assets/js/tablereorder.js");


//jimport( 'joomla.html.editor' );
//$editor	=JFactory::getEditor();

?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativealbum.cancel') {
		submitform( task );
	}
	else {
		if (form.album_name.value != ""){
			form.album_name.style.border = "1px solid green";
		} 
		if (form.album_name.value == ""){
			form.album_name.style.border = "1px solid red";
			form.album_name.focus();
		}
		else {	
			submitform( task );
		}
	}
}


		
</script>
<div id="cg_main_caption">
	<p><?php echo JText::_('COM_CREATIVEGALLERY_LOADING_PAGE_TEXT'); ?></p>
</div>
<div id="cg_tabs">
<ul>
	<li><a href="#tab1"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TAB1'); ?></a></li>
	<li><a href="#tab2"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TAB2'); ?></a></li>
	<li><a href="#tab3"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TAB3'); ?></a></li>
	<li><a href="#tab4"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TAB4'); ?></a></li>
	<li><a href="#tab5"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TAB5'); ?></a></li>
</ul>	
	
	


	<?php //print_r($this->images) ?>
	<?php //print_r($this->imgtags) ?>
	<?php //print_r($this->views) ?>
	<?php //print_r($this->lightboxes) ?>
	<?php //print_r($this->hovers) ?>
	<?php //echo $this->item->tmp_styles->seperator_color ?>


	<form action="<?php echo JRoute::_('index.php?option=com_creativegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" >
		<input type="hidden" name="id" value=<?php echo $this->item->id ?>>	
		<div id="tab1">
			<div class="options_wrapper">	
				<div class="wrapper">
					<label for="album_name" class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_NAME'); ?></label>
					<input type="text" name="cg_post[album][name]" id="album_name" placeholder="Album Name" autofocus class="left" value=<?php echo "'" . $this->item->name . "'"?>>	
				</div>
				<div class="wrapper">
					<label for="description" class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_DESCRIPTION'); ?></label>
					<input type="text" name="cg_post[album][description]" id="description" placeholder="Album Description" class="left" value=<?php echo "'" . $this->item->description . "'"?> >		
					<!-- <div id="editor_wrapper" class="left">
						echo $editor->display('description', '','500', '200', 10, 10);
					</div> -->
				</div>
				<div class="wrapper">
					<button type="button" id="add_preview" class="button_style left"><?php echo JText::_('COM_CREATIVEGALLERY_PREVIEW_IMAGE'); ?></button>
					<img id="cg_preview_image" class="sec_col left" src=<?php if ($this->item->prev_img) {echo "'" . $this->item->prev_img . "'";}  else {echo "components/com_creativegallery/assets/images/folder-generic.png";}?> alt="preview image">
					<input type="hidden" name="cg_post[album][preview_image]" value=<?php  if ($this->item->prev_img) {echo "'" . $this->item->prev_img . "'";} else { echo "components/com_creativegallery/assets/images/folder-generic.png";} ?>>		
				</div>
				<div class="wrapper">
					<div id="cg_album_tags" class="left">
						<div class="left" id="album_tag_template" style="display:none">
							<span class="tag_text" data-id=""></span>
							<img src="components/com_creativegallery/assets/images/delete.png" alt="delete_icon" class="delete_icon">
						</div>
					</div>
				</div>
			</div>
			<div class="wrapper" style="width: 500px">
				<button type="button" id="add_images_button" class="button_style"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_ADD_IMAGES_BUTTON'); ?></button>
				<button type="button" id="add_image_tags_button" class="button_style"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_ADD_TAGS_BUTTON'); ?></button>
				<button type="button" id="remove_images_button" class="button_style"><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_REMOVE_IMAGES_BUTTON'); ?></button>
			</div>
			<div>
				<table class="table-main">
					<thead>
						<th class="table-select"><input type="checkbox"/></th>
				    	<th class="table-reorder"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_ORDER'); ?></span></th>
				    	<th class="table-thumbnail"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_THUMBNAIL'); ?></span></th>
				    	<th class="table-image-name"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_IMAGE_NAME'); ?></span></th>
				    	<th class="table-title"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_DESCRIPTION'); ?></span></th>
				    	<th class="table-image-tags"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_TAGS'); ?></span></th>
				    	<th class="table-published"><span><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_TABLE_STATUS'); ?></span></th>
				    	<!-- <th class="scroller-compance"> </th> -->
				  	</thead>
				  	<tbody>
				  		<?php $ordering_arr = array(); ?>
				  		<?php foreach ($this->images as $key => $cur_img): ?>
			  			<?php $ordering_arr[] = 'item_' . $cur_img['ordering']?>
						<tr id=<?php echo "item_" . $cur_img['ordering'] ?>>
							<td class="table-select">
								<input type="checkbox">
							</td>
							<td class="table-reorder">
								<img src="components/com_creativegallery/assets/images/direction_vert.png" alt="reorder icon">
							</td>
							<td class="table-thumbnail">
								<img src=<?php if (filter_var($cur_img['path'], FILTER_VALIDATE_URL)) {echo "'" . $cur_img['path'] . "'";} else {$path_arr = explode("/", $cur_img['path']); $path_arr[ sizeof($path_arr)] = $path_arr[ sizeof($path_arr)-1]; $path_arr[ sizeof($path_arr)-2] = "cg_thumbnails"; echo "'" . implode("/", $path_arr) . "'"; }?> 	
									 alt=<?php echo "'" . $cur_img['name'] . "'"?>>
									<input type="hidden" value=<?php echo "'" . $cur_img['path'] . "'" ?> name="cg_post[item_<?php echo $cur_img['ordering'] ?>][path]">
							</td>
							<td class="table-image-name">
								<div>
									<span>name: </span>
									<input type="text" class="cg_image_name" name="cg_post[item_<?php echo $cur_img['ordering'] ?>][name]" value=<?php echo "'" . $cur_img['name'] . "'"?>>
								</div>
								<div>
									<span>title:</span>
									<input type="text" class="cg_image_title" name="cg_post[item_<?php echo $cur_img['ordering'] ?>][title]" value = <?php echo "'" . $cur_img['title'] . "'" ?> >
								</div>
								<div>
									<span>link(URL):</span>
									<input type="text" class="cg_image_link" name="cg_post[item_<?php echo $cur_img['ordering'] ?>][link]" value = <?php echo "'" . $cur_img['link'] . "'" ?> >
								</div>
								<div>
									<span>Target:</span>
									<select name="cg_post[item_<?php echo $cur_img['ordering'] ?>][target]" class="cg_image_select">
										<option value = '0' <?php  if($cur_img['target']=='0') { echo "selected"; }?>>Same Page</option>
										<option value = '1' <?php  if($cur_img['target']=='1') { echo "selected"; }?>>New Page</option>
									</select>
										
								</div>
							</td>
							<td class="table-title">
								<textarea class="table-image-description" name="cg_post[item_<?php echo $cur_img['ordering'] ?>][description]"><?php echo $cur_img['description'] ?></textarea>
							</td>
							<td class="table-image-tags">
								<?php foreach ($this->imgtags[$cur_img['id']] as $tag_id => $tag_name): ?>
								<?php if ($tag_id!=""): ?>
								<div class="left tag">
									<span class="tag_text" data-id=<?php echo "'" . $tag_id . "'"; ?>><?php echo $tag_name ;?></span>
									<img src="components/com_creativegallery/assets/images/delete.png" alt="delete_icon" class="delete_icon">
								</div>	
								<?php endif ?>
								<?php endforeach ?>
								<input type="hidden" value=<?php echo "'". implode(" ", array_keys($this->imgtags[$cur_img['id']])) . "'" ;?> name="cg_post[item_<?php echo $cur_img['ordering'] ?>][tags]">
							</td>
							<td class="table-published">
								<img src=<?php if ($cur_img['published']) {echo "components/com_creativegallery/assets/images/icons/published.png";} else { echo "components/com_creativegallery/assets/images/icons/not_published.png";}?> 			
									 title=<?php if ($cur_img['published']) { echo "'" . "Published and Current" . "'";} else { echo "'" . "Click to Publish" . "'"; }?>
									 alt="publish icon" class="cg_publish_icon">
								<input type="hidden" value=<?php echo "'" .$cur_img['published']. "'" ?> name="cg_post[item_<?php echo $cur_img['ordering'] ?>][publish]">
							</td>
						</tr>			  			
						<?php endforeach ?>
					</tbody>
				</table>
				<input id="cg_album_ordering" type="hidden" name="cg_post[album][ordering]" value=<?php if (is_array($ordering_arr)) { echo "'" . implode(" ", $ordering_arr ) . "'";	}  ?>>
			</div>
		</div>
		<div id="tab2">
			<div class="options_wrapper1">
					<div class="option_wrapper cg_style_manager">
						<div class="cg_section">
							<h2><?php echo JText::_('COM_CREATIVEGALLERY__ADMIN_PANEL_PREDEFINED_TEMPLATES'); ?></h2>
							<input id="cg_style_name" style="display:none" placeholder="Name">
							<button id="cg_confirm_button" style="display:none" class='styles_button button_style'>OK</button>
							<button id="cg_cancel_button" style="display:none" class='styles_button button_style'>Cancel</button>
							<div id="cg_view_loader_status">
								<div class="cg_progress"></div>
							</div>
						</div>
						<div class="cg_section">
							<select type="text" id="cg_predefined_styles" class="left">
								<option>-<?php echo JText::_('COM_CREATIVEGALLERY_SELECT'); ?>-</option>
								<?php foreach ($this->styles as $id => $name): ?>
									<option value=<?php echo "'" . $id . "'" ?> ><?php echo $name ?></option>
								<?php endforeach ?>
							</select>
							<button id="cg_style_load" class="styles_button button_style"><?php echo JText::_('COM_CREATIVEGALLERY_LOAD'); ?></button>
							<button id="cg_style_save" class="styles_button button_style"><?php echo JText::_('COM_CREATIVEGALLERY_SAVE'); ?></button>
							<button id="cg_style_save_as" class="styles_button button_style"><?php echo JText::_('COM_CREATIVEGALLERY_SAVE_AS'); ?>
								</button>
						</div>
					</div>
					<div class="option_wrapper">
						<label for="album_view" class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_VIEW'); ?>:</label>
						<select type="text" name="cg_post[album][view_id]" id="album_view" class="left">
							<?php foreach ($this->views as $id => $name): ?>
								<option value=<?php echo "'" . $id . "'" ?> <?php if (($this->item->view_id)==$id) { echo "selected";} ?>  ><?php echo $name ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_MAX_IMAGE_PER_PAGE'); ?></label>
						<div id="cg_max_image_per_page_selector" class="left cg_size_selector"></div>
						<div class="left cg_max_image_per_page_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][max_image_per_page]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->max_image_per_page . "'";}?>>
					</div>
					<div class="option_wrapper" style="display:none">
						<label for="appear_type" class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_APPEAR'); ?>:</label>
						<select type="text" name="cg_post[album][appear_id]" id="appear_type" class="left">
							<?php foreach ($this->appears as $id => $name): ?>
								<option value=<?php echo "'" . $id . "'" ?> <?php if (($this->item->appear_id)==$id) { echo "selected";} ?> ><?php echo $name ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_THUMBNAIL_SIZE'); ?></label>
						<div id="cg_img_size_selector" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][thumbnail_size]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->thumbnail_size . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_THUMBNAILS_COUNT'); ?></label>
						<div id="cg_img_count_selector" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][thumbnails_count]" value=<?php if ($this->item->id==0) {echo 3;} else {echo "'" . $this->item->thumbnails_count . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_IMAGES_MARGIN'); ?></label>
						<div id="cg_img_margin_selector" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][margin]" value=<?php if ($this->item->id==0) {echo 20;} else {echo "'" . $this->item->margin . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_IMAGES_PREVIEW_HEIGHT'); ?></label>
						<div id="cg_preview_height" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][tmp_styles][prev_height]" value=<?php if ($this->item->id==0) {echo 20;} else {echo "'" . $this->item->tmp_styles->prev_height . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_IMAGES_PREVIEW_SPEED'); ?></label>
						<div id="cg_preview_speed" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][tmp_styles][prev_speed]" value=<?php if ($this->item->id==0) {echo 20;} else {echo "'" . $this->item->tmp_styles->prev_speed . "'";}?>>
					</div>
					<div class="border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_SEPERATOR_OPTIONS'); ?></h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Margin</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input id="seperator_border_w_selector" name="cg_post[album][tmp_styles][seperator_width]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->seperator_width . "'";}?>></input>
							<input id="seperator_margin_selector" name="cg_post[album][tmp_styles][seperator_margin]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->seperator_margin . "'";}?>></input>
							<select id="seperator_border_t_selector" name="cg_post[album][tmp_styles][seperator_type]" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->seperator_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->seperator_type=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="seperator_border_o_selector" class="cg_border_selector"></input>
							<input id="seperator_border_colorpicker_input" type="hidden" name="cg_post[album][tmp_styles][seperator_color]" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->seperator_color . "'";}?>></input>
							<div id="seperator_border_colorpicker" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="bg_opt_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_CONTAINER_OPTIONS'); ?></h3>
						<div class="bg_opt_text_wrapper">
							<span>Padding</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="bg_opt_main_wrapper">
							<input name="cg_post[album][tmp_styles][cont_padding]" id="cg_cont_p_selector" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_styles->cont_padding . "'";}?>></input>
							<input id="cg_cont_o_selector" class="cg_bg_selector" ></input>
							<div id="cg_bg_colorpicker" class="bg_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][cont_bg_color]" type="hidden" id="cg_bg_colorpicker_input" class="cg_bg_colorpicker" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1)";} else {echo "'" . $this->item->tmp_styles->cont_bg_color . "'";}?>></input>
						</div>
					</div>
					<div class="border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_CONTAINER_BORDER'); ?></h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Radius</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input id="cg_cont_border_w_selector" name="cg_post[album][tmp_styles][cont_border_width]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_border_width . "'";}?>></input>
							<input id="cg_cont_border_r_selector" name="cg_post[album][tmp_styles][cont_border_radius]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_border_radius . "'";}?>></input>
							<select id="cg_cont_border_t_selector" name="cg_post[album][tmp_styles][cont_border_type]" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->cont_border_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_border_type=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="cg_cont_border_o_selector" class="cg_border_selector"></input>
							<input id="cont_border_colorpicker_input" type="hidden" name="cg_post[album][tmp_styles][cont_border_color]" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->cont_border_color . "'";}?>></input>
							<div id="cont_border_colorpicker" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_CONTAINER_BOX_SHADOW'); ?></h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][cont_boxsh_h]" id="cg_cont_box_shadow_h_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][cont_boxsh_v]" id="cg_cont_box_shadow_v_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_boxsh_v . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][cont_boxsh_blur]" id="cg_cont_box_shadow_b_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][cont_boxsh_spread]" id="cg_cont_box_shadow_s_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->cont_boxsh_spread . "'";}?>></input>
							<select name="cg_post[album][tmp_styles][cont_boxsh_type_selector]" id="cg_cont_box_shadow_t_selector" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->cont_boxsh_type_selector=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {;} else if ($this->item->tmp_styles->cont_boxsh_type_selector=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_cont_box_shadow_o_selector" class="cg_box_shadow_selector"></input>
							<div id="cont_box_shadow_colorpicker" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][cont_boxsh_color]" type="hidden" id="cont_box_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba( 0 , 0 , 0 , 1 )";} else {echo "'" . $this->item->tmp_styles->cont_boxsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_IMAGE_BORDER'); ?></h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Radius</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input name="cg_post[album][tmp_styles][img_border_width]" id="cg_image_border_w_selector" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_border_width . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_border_radius]" id="cg_image_border_r_selector" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_border_radius . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_border_radius_type]" id="cg_image_border_r_type_selector" type="hidden" value=<?php if ($this->item->id==0) {echo '%';} else {echo "'" . $this->item->tmp_styles->img_border_radius_type . "'";}?>></input>
							<select name="cg_post[album][tmp_styles][img_border_type]" id="cg_image_border_t_selector" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->img_border_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_border_type=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="cg_image_border_o_selector" class="cg_border_selector"></input>
							<input name="cg_post[album][tmp_styles][img_border_color]" type="hidden" id="image_border_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1)";} else {echo "'" . $this->item->tmp_styles->img_border_color . "'";}?>></input>
							<div id="image_border_colorpicker" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_IMAGE_BOX_SHADOW'); ?></h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][img_boxsh_h]" id="cg_image_box_shadow_h_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_boxsh_v]" id="cg_image_box_shadow_v_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_boxsh_v . "'";}?>></input>							
							<input name="cg_post[album][tmp_styles][img_boxsh_blur]" id="cg_image_box_shadow_b_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_boxsh_spread]" id="cg_image_box_shadow_s_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_boxsh_spread . "'";}?>></input>							
							<select name="cg_post[album][tmp_styles][img_boxsh_type]" id="cg_image_box_shadow_t_selector" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_boxsh_type=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_boxsh_type=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_image_box_shadow_o_selector" class="cg_box_shadow_selector"></input>
							<div id="image_box_shadow_colorpicker" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][img_boxsh_color]" type="hidden" id="image_box_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->img_boxsh_color . "'";}?>></input>
						</div>
					</div>					
					<div class="text_shadow_selector_wrapper main_text_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TEXT_SHADOW'); ?></h3>
						<div class="text_shadow_text_wrapper">
							<span>H-shadow</span>
							<span>V-shadow</span>
							<span>Blur</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][txtsh_h]" id="cg_text_shadow_h_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txtsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][txtsh_v]" id="cg_text_shadow_v_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txtsh_v . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][txtsh_blur]" id="cg_text_shadow_b_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txtsh_blur . "'";}?>></input>
							<input id="cg_text_shadow_o_selector" class="cg_text_shadow_selector"></input>
							<div id="text_shadow_colorpicker" class="text_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][txtsh_color]" type="hidden" id="text_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->txtsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="text_options_selector_wrapper main_text_options_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TEXT_OPTIONS'); ?></h3>
						<div class="text_main_text_wrapper">
							<span>Letter-spacing</span>
							<span>Word-space</span>
							<span>Line-height</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_styles][txt_letter_spacing]" id="cg_text_letter_spacing" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txt_letter_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][txt_word_spacing]" id="cg_text_word_spacing" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txt_word_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][txt_line_height]" id="cg_text_line_height" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txt_line_height . "'";}?>></input>
							<input id="cg_text_o_selector" class="cg_text_option_selector"></input>
							<div id="text_colorpicker" class="cg_text_option_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][txt_color]" type="hidden" id="text_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->txt_color . "'";}?>></input>
						</div>
						<div class="text_main_text_wrapper1">
							<span>Size</span>
							<span>Direction</span>
							<span>Unicode-bidi</span>
							<span>Text-decoration</span>
							<span>Text-transform</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_styles][txt_size]" id="cg_text_size" class="cg_text_option_selector2" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->txt_size . "'";}?>></input>
							<select name="cg_post[album][tmp_styles][txt_direction]" id="cg_text_direction" class="cg_text_option_selector4">
								<option value="ltr" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_direction=='ltr') {echo 'selected';}?>>ltr</option>
								<option value="rtl" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_direction=='rtl') {echo 'selected';}?>>rtl</option>
							</select>
							<select name="cg_post[album][tmp_styles][txt_unicode_bibi]" id="cg_tex_unicode_bibi" class="cg_text_option_selector1">
								<option value="normal" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_unicode_bibi=='normal') {echo 'selected';}?>>normal</option>
								<option value="embed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_unicode_bibi=='embed') {echo 'selected';}?>>embed</option>
								<option value="bidi-override" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_unicode_bibi=='bidi-override') {echo 'selected';}?>>bidi-override</option>
							</select>
							<select name="cg_post[album][tmp_styles][txt_decoration]" id="cg_text_decoration" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_decoration=='none') {echo 'selected';}?>>none</option>
								<option value="underline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_decoration=='underline') {echo 'selected';}?>>underline</option>
								<option value="overline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_decoration=='overline') {echo 'selected';}?>>overline</option>
								<option value="line-through" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_decoration=='line-through') {echo 'selected';}?>>line-through</option>
							</select>
							<select name="cg_post[album][tmp_styles][txt_transform]" id="cg_text_transform" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_transform=='none') {echo 'selected';}?>>none</option>
								<option value="capitalize" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_transform=='capitalize') {echo 'selected';}?>>capitalize</option>
								<option value="uppercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_transform=='uppercase') {echo 'selected';}?>>uppercase</option>
								<option value="lowercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->txt_transform=='lowercase') {echo 'selected';}?>>lowercase</option>
							</select>
						</div>
					</div>
			</div>
			<div class="preview_container">
				<h2><?php echo JText::_('COM_CREATIVEGALLERY_LIVE_PREVIEW'); ?></h2>
				<div id="cg_preview_container_wrapper_template" style="display:none">
					<div id="tags_template" style="display:none">
						<div>
							<span>Tag1</span>
							<span>Tag2</span>
							<span>Tag3</span>
							<span>Tag4</span>
						</div>
					</div>
					<div id="seperator_template_top" class="seperator" style="display:none">
					</div>
					<div class="cg_preview">
						<div id="cg_image_preview_template"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="tab3">
			<div class="options_wrapper1">
				<div class="option_wrapper_radio" id="cg_tag_state_selector">
					<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_STATE'); ?>:</label>
					<input type="radio" name="tag_state" value="normal" checked><span>Normal</span>
					<input type="radio" name="tag_state" value="hover"><span>Hover</span>
				</div>
				<div class="option_wrapper">
					<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_ANIMATION_SPEED'); ?></label>
					<div id="cg_tags_anim_speed" class="left cg_size_selector"></div>
					<div class="left cg_img_size_selector_value"><span></span></div>
					<input type="hidden" name="cg_post[album][tmp_tags_hover_styles][tags_anim_speed]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_anim_speed . "'";}?>>
				</div>
				<div id="cg_tags_design_normal">
					<div class="tag_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_OPTIONS'); ?></h3>
						<div class="tags_text_wrapper">
							<span>Enabled</span>
							<span>Margin</span>
							<span>Pad-H</span>
							<span>Pad-V</span>
							<span>BG-Opacity</span>
							<span>Color</span>
						</div>
						<div class="tags_main_wrapper">
							<select id="tags_emabled" name="cg_post[album][tmp_styles][tags_enabled]" class="cg_tags_enabled">
								<option value="1" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_enabled==1) {echo 'selected';}?>>ON</option>
								<option value="0" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_enabled==0) {echo 'selected';}?>>OFF</option>
							</select>
							<input id="tags_m_selector" name="cg_post[album][tmp_styles][tags_margin]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_styles->tags_margin . "'";}?>></input>
							<input id="tags_p_h_selector" name="cg_post[album][tmp_styles][tags_padding_h]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_styles->tags_padding_h . "'";}?>></input>
							<input id="tags_p_v_selector" name="cg_post[album][tmp_styles][tags_padding_v]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_styles->tags_padding_v . "'";}?>></input>
							<input id="tags_bg_o_selector" class="cg_bg_selector" ></input>
							<div id="tags_colorpicker" class="bg_selector_colorpicker"></div>
							<input id="cg_tags_bg_colorpicker_input" name="cg_post[album][tmp_styles][tags_bg_color]" type="hidden" class="cg_bg_colorpicker" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1)";} else {echo "'" . $this->item->tmp_styles->tags_bg_color . "'";}?>></input>
						</div>
					</div>
					<div class="border_selector_wrapper tags_border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_BORDER'); ?></h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Radius</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input id="cg_tags_border_w_selector" name="cg_post[album][tmp_styles][tags_border_width]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_border_width . "'";}?>></input>
							<input id="cg_tags_border_r_selector" name="cg_post[album][tmp_styles][tags_border_radius]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_border_radius . "'";}?>></input>
							<select id="cg_tags_border_t_selector" name="cg_post[album][tmp_styles][tags_border_type]" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->tags_border_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_border_type=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="cg_tags_border_o_selector" class="cg_border_selector"></input>
							<input id="tags_border_colorpicker_input" type="hidden" name="cg_post[album][tmp_styles][tags_border_color]" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->tags_border_color . "'";}?>></input>
							<div id="tags_border_colorpicker" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper tags_box_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_BOX_SHADOW'); ?></h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][tags_boxsh_h]" id="cg_tags_box_shadow_h_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_boxsh_v]" id="cg_tags_box_shadow_v_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_boxsh_v . "'";}?>></input>							
							<input name="cg_post[album][tmp_styles][tags_boxsh_blur]" id="cg_tags_box_shadow_b_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_boxsh_spread]" id="cg_tags_box_shadow_s_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_boxsh_spread . "'";}?>></input>							
							<select name="cg_post[album][tmp_styles][tags_boxsh_type]" id="cg_tags_box_shadow_t_selector" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_boxsh_type=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_boxsh_type=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_tags_box_shadow_o_selector" class="cg_box_shadow_selector"></input>
							<div id="tags_box_shadow_colorpicker" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][tags_boxsh_color]" type="hidden" id="tags_box_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->tags_boxsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="text_shadow_selector_wrapper tags_text_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_TEXT_SHADOW'); ?></h3>
						<div class="text_shadow_text_wrapper">
							<span>H-shadow</span>
							<span>V-shadow</span>
							<span>Blur</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][tags_txtsh_h]" id="cg_tags_text_shadow_h_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txtsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_txtsh_v]" id="cg_tags_text_shadow_v_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txtsh_v . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_txtsh_blur]" id="cg_tags_text_shadow_b_selector" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txtsh_blur . "'";}?>></input>
							<input id="cg_tags_text_shadow_o_selector" class="cg_text_shadow_selector"></input>
							<div id="tags_text_shadow_colorpicker" class="text_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][tags_txtsh_color]" type="hidden" id="tags_text_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->tags_txtsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="text_options_selector_wrapper tags_text_options_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_TEXT_OPTIONS'); ?></h3>
						<div class="text_main_text_wrapper">
							<span>Letter-spacing</span>
							<span>Word-space</span>
							<span>Line-height</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_styles][tags_txt_letter_spacing]" id="cg_tags_text_letter_spacing" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txt_letter_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_txt_word_spacing]" id="cg_tags_text_word_spacing" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txt_word_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][tags_txt_line_height]" id="cg_tags_text_line_height" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txt_line_height . "'";}?>></input>
							<input id="cg_tags_text_o_selector" class="cg_text_option_selector"></input>
							<div id="tags_text_colorpicker" class="cg_text_option_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][tags_txt_color]" type="hidden" id="tags_text_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->tags_txt_color . "'";}?>></input>
						</div>
						<div class="text_main_text_wrapper1">
							<span>Size</span>
							<span>Direction</span>
							<span>Unicode-bidi</span>
							<span>Text-decoration</span>
							<span>Text-transform</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_styles][tags_txt_size]" id="cg_tags_text_size" class="cg_text_option_selector2" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->tags_txt_size . "'";}?>></input>
							<select name="cg_post[album][tmp_styles][tags_txt_direction]" id="cg_tags_text_direction" class="cg_text_option_selector4">
								<option value="ltr" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_direction=='ltr') {echo 'selected';}?>>ltr</option>
								<option value="rtl" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_direction=='rtl') {echo 'selected';}?>>rtl</option>
							</select>
							<select name="cg_post[album][tmp_styles][tags_txt_unicode_bibi]" id="cg_tags_text_unicode_bibi" class="cg_text_option_selector1">
								<option value="normal" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_unicode_bibi=='normal') {echo 'selected';}?>>normal</option>
								<option value="embed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_unicode_bibi=='embed') {echo 'selected';}?>>embed</option>
								<option value="bidi-override" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_unicode_bibi=='bidi-override') {echo 'selected';}?>>bidi-override</option>
							</select>
							<select name="cg_post[album][tmp_styles][tags_txt_decoration]" id="cg_tags_text_decoration" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_decoration=='none') {echo 'selected';}?>>none</option>
								<option value="underline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_decoration=='underline') {echo 'selected';}?>>underline</option>
								<option value="overline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_decoration=='overline') {echo 'selected';}?>>overline</option>
								<option value="line-through" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_decoration=='line-through') {echo 'selected';}?>>line-through</option>
							</select>
							<select name="cg_post[album][tmp_styles][tags_txt_transform]" id="cg_tags_text_transform" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_transform=='none') {echo 'selected';}?>>none</option>
								<option value="capitalize" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_transform=='capitalize') {echo 'selected';}?>>capitalize</option>
								<option value="uppercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_transform=='uppercase') {echo 'selected';}?>>uppercase</option>
								<option value="lowercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->tags_txt_transform=='lowercase') {echo 'selected';}?>>lowercase</option>
							</select>
						</div>
					</div>
				</div>
				<div id="cg_tags_design_hover" style="display:none">
					<div class="tag_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_OPTIONS'); ?></h3>
						<div class="tags_text_wrapper">
							<!-- <span>Enabled</span> -->
							<span>Margin</span>
							<span>Pad-H</span>
							<span>Pad-V</span>
							<span>BG-Opacity</span>
							<span>Color</span>
						</div>
						<div class="tags_main_wrapper">
							<!-- <select id="tags_emabled" name="cg_post[album][tmp_tags_hover_styles][tags_enabled]" class="cg_tags_enabled">
								<option value="1" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_enabled==1) {echo 'selected';}?>>ON</option>
								<option value="0" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_enabled==0) {echo 'selected';}?>>OFF</option>
							</select> -->
							<input id="tags_m_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_margin]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_margin . "'";}?>></input>
							<input id="tags_p_h_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_padding_h]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_padding_h . "'";}?>></input>
							<input id="tags_p_v_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_padding_v]" class="cg_bg_selector" value=<?php if ($this->item->id==0) {echo "'" . 5 . "'";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_padding_v . "'";}?>></input>
							<input id="tags_bg_o_selector_hover" class="cg_bg_selector" ></input>
							<div id="tags_colorpicker_hover" class="bg_selector_colorpicker"></div>
							<input id="cg_tags_bg_colorpicker_input_hover" name="cg_post[album][tmp_tags_hover_styles][tags_bg_color]" type="hidden" class="cg_bg_colorpicker" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1)";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_bg_color . "'";}?>></input>
						</div>
					</div>
					<div class="border_selector_wrapper tags_border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_BORDER'); ?></h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Radius</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input id="cg_tags_border_w_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_border_width]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_border_width . "'";}?>></input>
							<input id="cg_tags_border_r_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_border_radius]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_border_radius . "'";}?>></input>
							<select id="cg_tags_border_t_selector_hover" name="cg_post[album][tmp_tags_hover_styles][tags_border_type]" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_border_type=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="cg_tags_border_o_selector_hover" class="cg_border_selector"></input>
							<input id="tags_border_colorpicker_input_hover" type="hidden" name="cg_post[album][tmp_tags_hover_styles][tags_border_color]" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_border_color . "'";}?>></input>
							<div id="tags_border_colorpicker_hover" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper tags_box_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_BOX_SHADOW'); ?></h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_h]" id="cg_tags_box_shadow_h_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_v]" id="cg_tags_box_shadow_v_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_boxsh_v . "'";}?>></input>							
							<input name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_blur]" id="cg_tags_box_shadow_b_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_spread]" id="cg_tags_box_shadow_s_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_boxsh_spread . "'";}?>></input>							
							<select name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_type]" id="cg_tags_box_shadow_t_selector_hover" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_boxsh_type=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_boxsh_type=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_tags_box_shadow_o_selector_hover" class="cg_box_shadow_selector"></input>
							<div id="tags_box_shadow_colorpicker_hover" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_boxsh_color]" type="hidden" id="tags_box_shadow_colorpicker_input_hover" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_boxsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="text_shadow_selector_wrapper tags_text_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_TEXT_SHADOW'); ?></h3>
						<div class="text_shadow_text_wrapper">
							<span>H-shadow</span>
							<span>V-shadow</span>
							<span>Blur</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_shadow_main_wrapper">
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txtsh_h]" id="cg_tags_text_shadow_h_selector_hover" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txtsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txtsh_v]" id="cg_tags_text_shadow_v_selector_hover" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txtsh_v . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txtsh_blur]" id="cg_tags_text_shadow_b_selector_hover" class="cg_text_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txtsh_blur . "'";}?>></input>
							<input id="cg_tags_text_shadow_o_selector_hover" class="cg_text_shadow_selector"></input>
							<div id="tags_text_shadow_colorpicker_hover" class="text_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txtsh_color]" type="hidden" id="tags_text_shadow_colorpicker_input_hover" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txtsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="text_options_selector_wrapper tags_text_options_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_TAG_TEXT_OPTIONS'); ?></h3>
						<div class="text_main_text_wrapper">
							<span>Letter-spacing</span>
							<span>Word-space</span>
							<span>Line-height</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txt_letter_spacing]" id="cg_tags_text_letter_spacing_hover" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txt_letter_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txt_word_spacing]" id="cg_tags_text_word_spacing_hover" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txt_word_spacing . "'";}?>></input>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txt_line_height]" id="cg_tags_text_line_height_hover" class="cg_text_option_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txt_line_height . "'";}?>></input>
							<input id="cg_tags_text_o_selector_hover" class="cg_text_option_selector"></input>
							<div id="tags_text_colorpicker_hover" class="cg_text_option_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txt_color]" type="hidden" id="tags_text_colorpicker_input_hover" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txt_color . "'";}?>></input>
						</div>
						<div class="text_main_text_wrapper1">
							<span>Size</span>
							<span>Direction</span>
							<span>Unicode-bidi</span>
							<span>Text-decoration</span>
							<span>Text-transform</span>
						</div>
						<div class="text_main_wrapper">
							<input name="cg_post[album][tmp_tags_hover_styles][tags_txt_size]" id="cg_tags_text_size_hover" class="cg_text_option_selector2" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_tags_hover_styles->tags_txt_size . "'";}?>></input>
							<select name="cg_post[album][tmp_tags_hover_styles][tags_txt_direction]" id="cg_tags_text_direction_hover" class="cg_text_option_selector4">
								<option value="ltr" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_direction=='ltr') {echo 'selected';}?>>ltr</option>
								<option value="rtl" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_direction=='rtl') {echo 'selected';}?>>rtl</option>
							</select>
							<select name="cg_post[album][tmp_tags_hover_styles][tags_txt_unicode_bibi]" id="cg_tags_text_unicode_bibi_hover" class="cg_text_option_selector1">
								<option value="normal" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_unicode_bibi=='normal') {echo 'selected';}?>>normal</option>
								<option value="embed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_unicode_bibi=='embed') {echo 'selected';}?>>embed</option>
								<option value="bidi-override" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_unicode_bibi=='bidi-override') {echo 'selected';}?>>bidi-override</option>
							</select>
							<select name="cg_post[album][tmp_tags_hover_styles][tags_txt_decoration]" id="cg_tags_text_decoration_hover" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_decoration=='none') {echo 'selected';}?>>none</option>
								<option value="underline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_decoration=='underline') {echo 'selected';}?>>underline</option>
								<option value="overline" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_decoration=='overline') {echo 'selected';}?>>overline</option>
								<option value="line-through" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_decoration=='line-through') {echo 'selected';}?>>line-through</option>
							</select>
							<select name="cg_post[album][tmp_tags_hover_styles][tags_txt_transform]" id="cg_tags_text_transform_hover" class="cg_text_option_selector1">
								<option value="none" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_transform=='none') {echo 'selected';}?>>none</option>
								<option value="capitalize" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_transform=='capitalize') {echo 'selected';}?>>capitalize</option>
								<option value="uppercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_transform=='uppercase') {echo 'selected';}?>>uppercase</option>
								<option value="lowercase" <?php if ($this->item->id==0) {} else if ($this->item->tmp_tags_hover_styles->tags_txt_transform=='lowercase') {echo 'selected';}?>>lowercase</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="preview_container_tags">
				<h2><?php echo JText::_('COM_CREATIVEGALLERY_LIVE_PREVIEW'); ?></h2>
				<h3>Normal State</h3>
				<div id="tags_template_normal" class="tags_container">
					<div>
						<span>Tag1</span>
						<span>Tag2</span>
						<span>Tag3</span>
						<span>Tag4</span>
					</div>
				</div>
				<h3>Hover State</h3>
				<div id="tags_template_hover" class="tags_container">
					<div>
						<span>Tag1</span>
						<span>Tag2</span>
						<span>Tag3</span>
						<span>Tag4</span>
					</div>
				</div>
				<h3>Animation (Hover To View)</h3>
				<div id="tags_template_animated" class="tags_container">
					<div>
						<span>Tag1</span>
						<span>Tag2</span>
						<span>Tag3</span>
						<span>Tag4</span>
					</div>
				</div>
				
			</div>
			<div class="clear"></div>
		</div>
		<div id="tab4">
			<div class="options_wrapper">
				<div class="options_wrapper1">
					<div class="option_wrapper">
						<label for="hover_type" class="left first_col">Hover Animation:</label>
						<select type="text" name="cg_post[album][hover_id]" id="hover_type" class="left">
							<?php foreach ($this->hovers as $id => $name): ?>
								<option value=<?php echo "'" . $id . "'" ?> <?php if (($this->item->hover_id)==$id) { echo "selected";} ?> ><?php echo $name ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col">Animation Speed</label>
						<div id="cg_img_anim_speed" class="left cg_size_selector"></div>
						<div class="left cg_img_size_selector_value"><span></span></div>
						<input type="hidden" name="cg_post[album][tmp_styles_img_hover][img_anim_speed]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->tmp_styles_img_hover->img_anim_speed . "'";}?>>
					</div>
					<div class="border_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_IMAGE_BORDER'); ?></h3>
						<div class="border_text_wrapper">
							<!-- <span>Width</span> -->
							<span>Radius</span>
							<!-- <span>Type</span> -->
							<span>Opacity</span>
							<span class="exeption1">Color</span>
						</div>
						<div class="border_main_wrapper">
							<!-- <input name="cg_post[album][tmp_styles_img_hover][img_border_width]" id="cg_image_border_w_selector_hover" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_border_width . "'";}?>></input> -->
							<input name="cg_post[album][tmp_styles_img_hover][img_border_radius]" id="cg_image_border_r_selector_hover" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_border_radius . "'";}?>></input>
							<!-- <select name="cg_post[album][tmp_styles_img_hover][img_border_type]" id="cg_image_border_t_selector_hover" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles_img_hover->img_border_type=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_border_type=='outset') {echo 'selected';}?>>Outset</option>
							</select> -->
							<input id="cg_image_border_o_selector_hover" class="cg_border_selector"></input>
							<input name="cg_post[album][tmp_styles_img_hover][img_border_color]" type="hidden" id="image_border_colorpicker_input_hover" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1)";} else {echo "'" . $this->item->tmp_styles_img_hover->img_border_color . "'";}?>></input>
							<div id="image_border_colorpicker_hover" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper">
						<h3><?php echo JText::_('COM_CREATIVEGALLERY_IMAGE_BOX_SHADOW'); ?></h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles_img_hover][img_boxsh_h]" id="cg_image_box_shadow_h_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles_img_hover][img_boxsh_v]" id="cg_image_box_shadow_v_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_boxsh_v . "'";}?>></input>							
							<input name="cg_post[album][tmp_styles_img_hover][img_boxsh_blur]" id="cg_image_box_shadow_b_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_styles_img_hover][img_boxsh_spread]" id="cg_image_box_shadow_s_selector_hover" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles_img_hover->img_boxsh_spread . "'";}?>></input>							
							<select name="cg_post[album][tmp_styles_img_hover][img_boxsh_type]" id="cg_image_box_shadow_t_selector_hover" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_boxsh_type=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles_img_hover->img_boxsh_type=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_image_box_shadow_o_selector_hover" class="cg_box_shadow_selector"></input>
							<div id="image_box_shadow_colorpicker_hover" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles_img_hover][img_boxsh_color]" type="hidden" id="image_box_shadow_colorpicker_input_hover" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles_img_hover->img_boxsh_color . "'";}?>></input>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper icons_selector_wrapper">
						<h3>Icon Options</h3>
						<div class="box_shadow_text_wrapper">
							<span>Appearance</span>
							<span>Zoom</span>
							<span>Link</span>
							<span>Effect</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<select name="cg_post[album][tmp_styles][img_icon_type]" id="cg_image_icon_type_selector" class="cg_image_type_selector">
								<option value="both" <?php if ($this->item->id==0) {'selected';} else if ($this->item->tmp_styles->img_icon_type=='both') {echo 'selected';}?>>Both</option>
								<option value="link_only" <?php if ($this->item->id==0) {'';} else if ($this->item->tmp_styles->img_icon_type=='link_only') {echo 'selected';}?>>Link</option>
								<option value="zoom_only" <?php if ($this->item->id==0) {'';} else if ($this->item->tmp_styles->img_icon_type=='zoom_only') {echo 'selected';}?>>Zoom</option>
								<option value="none" <?php if ($this->item->id==0) {'';} else if ($this->item->tmp_styles->img_icon_type=='none') {echo 'selected';}?>>None</option>
							<select>
							<select name="cg_post[album][tmp_styles][img_zoom_template]" id="cg_image_icon_zoom_template_selector" class="cg_image_type_selector">
								<option value="zoom1" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->img_zoom_template=='zoom1') {echo 'selected';}?>>Type1</option>
								<option value="zoom1_1" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom1_1') {echo 'selected';}?>>Type2</option>
								<option value="zoom2" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom2') {echo 'selected';}?>>Type3</option>
								<option value="zoom2_2" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom2_2') {echo 'selected';}?>>Type4</option>
								<option value="zoom3" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom3') {echo 'selected';}?>>Type5</option>
								<option value="zoom3_3" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom3_3') {echo 'selected';}?>>Type6</option>
								<option value="zoom4" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom4') {echo 'selected';}?>>Type7</option>
								<option value="zoom4_4" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom4_4') {echo 'selected';}?>>Type8</option>
								<option value="zoom5" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom5') {echo 'selected';}?>>Type9</option>
								<option value="zoom5_5" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom5_5') {echo 'selected';}?>>Type10</option>
								<option value="zoom6" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom6') {echo 'selected';}?>>Type11</option>
								<option value="zoom6_6" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom6_6') {echo 'selected';}?>>Type12</option>
								<option value="zoom7" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom7') {echo 'selected';}?>>Type13</option>
								<option value="zoom7_7" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom7_7') {echo 'selected';}?>>Type14</option>
								<option value="zoom8" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom8') {echo 'selected';}?>>Type15</option>		
								<option value="zoom9" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom9') {echo 'selected';}?>>Type16</option>		
								<option value="zoom10" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom10') {echo 'selected';}?>>Type17</option>		
								<option value="zoom11" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom11') {echo 'selected';}?>>Type18</option>		
								<option value="zoom12" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom12') {echo 'selected';}?>>Type19</option>		
								<option value="zoom13" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom13') {echo 'selected';}?>>Type20</option>		
								<option value="zoom14" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_zoom_template=='zoom14') {echo 'selected';}?>>Type21</option>		
							</select>
							<select name="cg_post[album][tmp_styles][img_link_template]" id="cg_image_icon_link_template_selector" class="cg_image_type_selector">
								<option value="link1" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->img_link_template=='link1') {echo 'selected';}?>>Type1</option>
								<option value="link1_1" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link1_1') {echo 'selected';}?>>Type2</option>
								<option value="link2" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link2') {echo 'selected';}?>>Type3</option>
								<option value="link2_2" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link2_2') {echo 'selected';}?>>Type4</option>
								<option value="link3" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link3') {echo 'selected';}?>>Type5</option>
								<option value="link3_3" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link3_3') {echo 'selected';}?>>Type6</option>
								<option value="link4" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link4') {echo 'selected';}?>>Type7</option>
								<option value="link4_4" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link4_4') {echo 'selected';}?>>Type8</option>
								<option value="link5" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link5') {echo 'selected';}?>>Type9</option>
								<option value="link5_5" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link5_5') {echo 'selected';}?>>Type10</option>
								<option value="link6" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link6') {echo 'selected';}?>>Type11</option>
								<option value="link6_6" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link6_6') {echo 'selected';}?>>Type12</option>
								<option value="link7" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link7') {echo 'selected';}?>>Type13</option>
								<option value="link7_7" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link7_7') {echo 'selected';}?>>Type14</option>
								<option value="link8" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link8') {echo 'selected';}?>>Type15</option>		
								<option value="link9" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link9') {echo 'selected';}?>>Type16</option>		
								<option value="link10" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link10') {echo 'selected';}?>>Type17</option>		
								<option value="link11" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link11') {echo 'selected';}?>>Type18</option>		
								<option value="link12" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link12') {echo 'selected';}?>>Type19</option>		
								<option value="link13" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link13') {echo 'selected';}?>>Type20</option>		
								<option value="link14" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_link_template=='link14') {echo 'selected';}?>>Type21</option>		
							</select>
							<select name="cg_post[album][tmp_styles][img_icon_effect]" id="cg_image_icon_effect_selector" class="cg_image_type_selector">
								<option value="1" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->img_icon_effect=='1') {echo 'selected';}?>>Effect1</option>
								<option value="2" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_effect=='2') {echo 'selected';}?>>Effect2</option>
								<option value="3" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_effect=='3') {echo 'selected';}?>>Effect3</option>
								<option value="4" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_effect=='4') {echo 'selected';}?>>Effect4</option>
								<option value="5" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_effect=='5') {echo 'selected';}?>>Effect5</option>
							</select>
						</div>
					</div>	
					<div class="box_shadow_selector_wrapper icon_property_selector_wrapper">
						<h3>Icon Options</h3>
						<div class="box_shadow_text_wrapper">
							<span>Size</span>
							<span>Proportion</span>
							<span>Top1</span>
							<span>Left1</span>
							<span>Top2</span>
							<span>Left2</span>
							<span>Color</span>
							<span>Opacity</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][img_icon_width]" id="cg_img_icon_width_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_width . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_icon_prop]" id="cg_img_icon_prop_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_prop . "'";}?>></input>							
							<input name="cg_post[album][tmp_styles][img_icon_top1]" id="cg_img_icon_top1_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_top1 . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_icon_left1]" id="cg_img_icon_left1_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_left1 . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_icon_top2]" id="cg_img_icon_top2_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_top2 . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][img_icon_left2]" id="cg_img_icon_left2_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_left2 . "'";}?>></input>
							<input id="cg_image_icon_o_selector" class="cg_box_shadow_selector"></input>
							<div id="cg_img_icon_colorpicker" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][img_icon_color]" type="hidden" id="img_icon_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->img_icon_color . "'";}?>></input>
						</div>
					</div>
					<div class="border_selector_wrapper icons_border_selector_wrapper">
						<h3>Icons Border</h3>
						<div class="border_text_wrapper">
							<span>Width</span>
							<span>Radius</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="border_main_wrapper">
							<input id="cg_icons_border_w_selector" name="cg_post[album][tmp_styles][img_icon_border_w]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_border_w . "'";}?>></input>
							<input id="cg_icons_border_r_selector" name="cg_post[album][tmp_styles][img_icon_border_r]" class="cg_border_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->img_icon_border_r . "'";}?>></input>
							<select id="cg_icons_border_t_selector" name="cg_post[album][tmp_styles][img_icon_border_t]" class="cg_border_type_selector">
									<option value="solid" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->img_icon_border_t=='solid') {echo 'selected';}?>>Solid</option>
									<option value="dotted" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='dotted') {echo 'selected';}?>>Dotted</option>
									<option value="dashed" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='dashed') {echo 'selected';}?>>Dashed</option>
									<option value="double" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='double') {echo 'selected';}?>>Double</option>
									<option value="groove" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='groove') {echo 'selected';}?>>Groove</option>
									<option value="ridge" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='ridge') {echo 'selected';}?>>Ridge</option>
									<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='inset') {echo 'selected';}?>>Inset</option>
									<option value="outset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->img_icon_border_t=='outset') {echo 'selected';}?>>Outset</option>
							</select>
							<input id="cg_icons_border_o_selector" class="cg_border_selector"></input>
							<input id="icons_border_colorpicker_input" type="hidden" name="cg_post[album][tmp_styles][img_icon_border_color]" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->img_icon_border_color . "'";}?>></input>
							<div id="icons_border_colorpicker" class="border_selector_colorpicker"></div>
						</div>
					</div>
					<div class="box_shadow_selector_wrapper icons_box_shadow_selector_wrapper">
						<h3>Icons Box Shadow</h3>
						<div class="box_shadow_text_wrapper">
							<span>H-sh</span>
							<span>V-sh</span>
							<span>Blur</span>
							<span>Spread</span>
							<span>Type</span>
							<span>Opacity</span>
							<span>Color</span>
						</div>
						<div class="box_shadow_main_wrapper">
							<input name="cg_post[album][tmp_styles][icons_boxsh_h]" id="cg_icons_box_shadow_h_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->icons_boxsh_h . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][icons_boxsh_v]" id="cg_icons_box_shadow_v_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->icons_boxsh_v . "'";}?>></input>							
							<input name="cg_post[album][tmp_styles][icons_boxsh_blur]" id="cg_icons_box_shadow_b_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->icons_boxsh_blur . "'";}?>></input>
							<input name="cg_post[album][tmp_styles][icons_boxsh_spread]" id="cg_icons_box_shadow_s_selector" class="cg_box_shadow_selector" value=<?php if ($this->item->id==0) {echo 0;} else {echo "'" . $this->item->tmp_styles->icons_boxsh_spread . "'";}?>></input>							
							<select name="cg_post[album][tmp_styles][icons_boxsh_type]" id="cg_icons_box_shadow_t_selector" class="cg_box_shadow_type_selector">
								<option value="" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->icons_boxsh_type=='outset') {echo 'selected';}?>>Out</option>
								<option value="inset" <?php if ($this->item->id==0) {} else if ($this->item->tmp_styles->icons_boxsh_type=='inset') {echo 'selected';}?>>In</option>
							</select>
							<input id="cg_icons_box_shadow_o_selector" class="cg_box_shadow_selector"></input>
							<div id="icons_box_shadow_colorpicker" class="box_shadow_selector_colorpicker"></div>
							<input name="cg_post[album][tmp_styles][icons_boxsh_color]" type="hidden" id="icons_box_shadow_colorpicker_input" value=<?php if ($this->item->id==0) {echo "rgba(0,0,0,1 )";} else {echo "'" . $this->item->tmp_styles->icons_boxsh_color . "'";}?>></input>
						</div>
					</div>

				</div>
			</div>
			<div class="preview_container_images">
				<h2><?php echo JText::_('COM_CREATIVEGALLERY_LIVE_PREVIEW'); ?></h2>
				<h3>Normal State</h3>
				<div class="image_preview_container">
					<div id="image_normal" class="main_wrapper"> 
						<div class="image_wrapper">
							<img class="main_image" src="components/com_creativegallery/assets/images/preview.jpg">
							<div class="overlay"></div>
							<div class="icon icon_zoom"></div>
							<div class="icon icon_link"></div>
						</div>
					</div>
				</div>
				<h3>Hover State</h3>
				<div class="image_preview_container">
					<div id="image_hover" class="main_wrapper"> 
						<div class="image_wrapper">
							<img class="main_image" src="components/com_creativegallery/assets/images/preview.jpg">
							<div class="overlay"></div>
							<div class="icon icon_zoom"></div>
							<div class="icon icon_link"></div>
						</div>
					</div>
				</div>					
				<h3>Animated</h3>
				<div class="image_preview_container">
					<div id="image_animated" class="main_wrapper"> 
						<div class="image_wrapper">
							<img class="main_image" src="components/com_creativegallery/assets/images/preview.jpg">
							<div class="overlay"></div>
							<div class="icon icon_zoom"></div>
							<div class="icon icon_link"></div>
						</div>
					</div>
				</div>					
			</div>
			<div class="clear"></div>
		</div>
		<div id="tab5">
			<div class="options_wrapper lightbox-options">
				<div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_LIGHTBOX_TYPE'); ?>:</label>
						<select type="text" name="cg_post[album][tmp_styles][lightbox_type]" class="left">
							<option value="horizontal" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->lightbox_type=="horizontal") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_LIGHTBOX_TYPE_HORIZONTAL'); ?>
								</option>
							<option value="vertical" <?php if ($this->item->id==0) {echo '';} else if ($this->item->tmp_styles->lightbox_type=="vertical") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_LIGHTBOX_TYPE_VERTICAL'); ?>
								</option>
							<option value="map" <?php if ($this->item->id==0) {echo '';} else if ($this->item->tmp_styles->lightbox_type=="map") {echo 'selected';}?>><?php echo JText::_('COM_CREATIVEGALLERY_LIGHTBOX_TYPE_MAP'); ?>
								</option>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_TRANSITION_DELAY'); ?>:</label>
						<input type="number" min="100" max="1000" step="10" name="cg_post[album][tmp_styles][lightbox_transition_delay]" value=<?php if ($this->item->id==0) {echo 300;} else {echo "'" . $this->item->tmp_styles->lightbox_transition_delay . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_THUMBNAIL_WIDTH'); ?>:</label>
						<input type="number" min="100" max="300" step="10" name="cg_post[album][tmp_styles][lightbox_thumbnail_width]" value=<?php if ($this->item->id==0) {echo 150;} else {echo "'" . $this->item->tmp_styles->lightbox_thumbnail_width . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_THUMBNAIL_HEIGHT'); ?>:</label>
						<input type="number" min="100" max="300" step="10" name="cg_post[album][tmp_styles][lightbox_thumbnail_height]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->tmp_styles->lightbox_thumbnail_height . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAPTION_ENABLED'); ?>:</label>
						<select type="text" name="cg_post[album][tmp_styles][lightbox_caption_enebled]" class="left">
							<option value="true" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->lightbox_caption_enebled=="true") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_TRUE'); ?>
								</option>
							<option value="false" <?php if ($this->item->id==0) {echo '';} else if ($this->item->tmp_styles->lightbox_caption_enebled=="false") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_FALSE'); ?>
								</option>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAPTION_HEIGHT'); ?>:</label>
						<input type="number" min="100" max="300" step="10" name="cg_post[album][tmp_styles][lightbox_caption_height]" value=<?php if ($this->item->id==0) {echo 100;} else {echo "'" . $this->item->tmp_styles->lightbox_caption_height . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_IMAGE_LOADING_TIMEOUT'); ?>:</label>
						<input type="number" min="1000" max="10000" step="100" name="cg_post[album][tmp_styles][lightbox_image_loading_timeout]" value=<?php if ($this->item->id==0) {echo 3000;} else {echo "'" . $this->item->tmp_styles->lightbox_image_loading_timeout . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAROUSEL_IMAGES_MARGIN'); ?>:</label>
						<input type="number" min="0" max="20" step="1" name="cg_post[album][tmp_styles][lightbox_carousel_images_margin]" value=<?php if ($this->item->id==0) {echo 5;} else {echo "'" . $this->item->tmp_styles->lightbox_carousel_images_margin . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAROUSEL_MAP_SIZE'); ?>:</label>
						<input type="number" min="5" max="20" step="1" name="cg_post[album][tmp_styles][lightbox_carousel_map_size]" value=<?php if ($this->item->id==0) {echo 10;} else {echo "'" . $this->item->tmp_styles->lightbox_carousel_map_size . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAROUSEL_MAP_ICONS_MAX_VISIBLE'); ?>:</label>
						<input type="number" min="3" max="30" step="1" name="cg_post[album][tmp_styles][lightbox_carousel_map_icons_max_visible]" value=<?php if ($this->item->id==0) {echo 20;} else {echo "'" . $this->item->tmp_styles->lightbox_carousel_map_icons_max_visible . "'";}?>>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_AUTOPLAY_ENABLED'); ?>:</label>
						<select type="text" name="cg_post[album][tmp_styles][lightbox_autoplay_enebled]" class="left">
							<option value="true" <?php if ($this->item->id==0) {echo 'selected';} else if ($this->item->tmp_styles->lightbox_autoplay_enebled=="true") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_TRUE'); ?>
							</option>
							<option value="false" <?php if ($this->item->id==0) {echo '';} else if ($this->item->tmp_styles->lightbox_autoplay_enebled=="false") {echo 'selected';} ?>><?php echo JText::_('COM_CREATIVEGALLERY_FALSE'); ?>
							</option>
						</select>
					</div>
					<div class="option_wrapper">
						<label class="left first_col"><?php echo JText::_('COM_CREATIVEGALLERY_CAROUSEL_SLIDE_SPEED'); ?>:</label>
						<input type="number" min="1" max="30" step="1" name="cg_post[album][tmp_styles][lightbox_carousel_slide_speed]" value=<?php if ($this->item->id==0) {echo 10;} else {echo "'" . $this->item->tmp_styles->lightbox_carousel_slide_speed . "'";}?>>
					</div>
				</div>
			</div>
		</div>
			<input type="hidden" name="task" value="creativealbum.add" />
			<?php echo JHtml::_('form.token'); ?>	

		<div id="add_tags_dialog">
			<div id="add_tags_part">
				<img src="components/com_creativegallery/assets/images/icons/services.png" alt="manage_icon" title="manage" class="right">
				<div id="tagmanager_line_template" style="display:none"> 
					<div class="tagmanager_part">
						<select name="tag">
						
						</select>
					</div>
					<div class="tagmanager_part">
						<img src="components/com_creativegallery/assets/images/icons/plus.png" alt="more_icon" title="more">
						<img src="components/com_creativegallery/assets/images/icons/remove.png" alt="remove_icon" title="remove">
					</div>
				</div>
			</div>
			<div id="manage_tags_part" style="display:none;opacity: 0">
				<img src="components/com_creativegallery/assets/images/icons/back.png" alt="back_icon" title="back" class="right">
				<div id="tagmanager_manage_template" style="display:none"> 
					<div class="tagmanager_part">
						<span></span>
					</div>
					<div class="tagmanager_part">
						<img src="components/com_creativegallery/assets/images/icons/edit.png" alt="edit_icon" title="edit">
						<img src="components/com_creativegallery/assets/images/icons/remove.png" alt="remove_icon" title="remove">
						<img src="components/com_creativegallery/assets/images/icons/add_list.png" alt="add_icon" title="New">
					</div>
				</div>
			</div>
			<div id="new_tag_dialog">
				<label>Name</label>
				<span></span>
				<input type="text">
			</div>
			<div id="tags_confirm_dialog"><span>This Will Completely Remove Tag from Database</span></div>
			<div id="tags_info_dialog"><span>Please Select At Least On Image to Add Tags</span></div>
		</div>
		<div id="filemanager_dialog">
			<div class="cg_filemanager" id="filemanager_wrapper">
						<div id="buttons_navi">
							<button class="icons" id="cg_home">
								<img src="components/com_creativegallery/assets/images/icons/home.png" alt="home_icon">
								<span>Home</span>
							</button>
							<button class="icons" id="cg_up">
								<img src="components/com_creativegallery/assets/images/icons/back_top.png" alt="back_top_icon">
								<span>Up</span>
							</button>
						</div>
						<div id="buttons_edit">
							<button class="icons" id="cg_make_dir">
								<img src="components/com_creativegallery/assets/images/icons/folder.png" alt="folder_icon">
								<span>New</span>
							</button>
							<button class="icons" id="cg_copy">
								<img src="components/com_creativegallery/assets/images/icons/clipboard_copy.png" alt="clipboard_copy_icon">
								<span>Copy</span>
							</button>
							<button class="icons" id="cg_cut">
								<img src="components/com_creativegallery/assets/images/icons/clipboard_cut.png" alt="clipboard_cut_icon">
								<span>Cut</span>
							</button>
							<button class="icons" id="cg_paste">
								<img src="components/com_creativegallery/assets/images/icons/clipboard_paste.png" alt="clipboard_paste_icon">
								<span>Paste</span>
							</button>
							<button class="icons" id="cg_remove">
								<img src="components/com_creativegallery/assets/images/icons/trash.png" alt="trash_icon">
								<span>Remove</span>
							</button>
							<button class="icons" id="cg_rename">
								<img src="components/com_creativegallery/assets/images/icons/rename.png" alt="trash_icon">
								<span>Rename</span>
							</button>
						</div>
						<div id="buttons_insert">
							<button class="icons" id="cg_upload">
								<img src="components/com_creativegallery/assets/images/icons/download.png" alt="download_icon">
								<span>Upload</span>
							</button>
							<button class="icons" id="cg_weblink">
								<img src="components/com_creativegallery/assets/images/icons/link.png" alt="link_icon">
								<span>Weblink</span>
							</button>
						</div>
						<div id="buttons_view">
							<button class="icons" id="cg_select_all">
								<img src="components/com_creativegallery/assets/images/icons/ok.png" alt="table_icon">
								<span>All</span>
							</button>
							<button class="icons" id="cg_deselect_all">
								<img src="components/com_creativegallery/assets/images/icons/cancel.png" alt="tree_icon">
								<span>None</span>
							</button>
						</div>
						<div id="loading_wrapper">
							<div class="windows8">
								<div class="wBall" id="wBall_1">
									<div class="wInnerBall"></div>
								</div>
								<div class="wBall" id="wBall_2">
									<div class="wInnerBall"></div>
								</div>
								<div class="wBall" id="wBall_3">
									<div class="wInnerBall"></div>
								</div>
								<div class="wBall" id="wBall_4">
									<div class="wInnerBall"></div>
								</div>
								<div class="wBall" id="wBall_5">
									<div class="wInnerBall"></div>
								</div>
							</div>
						</div>
						<ul id="directory_tree"></ul>
						<ul id="responsebox" class="data animated"></ul>
			</div>
			<div id="fileupload_wrapper">
						<div id="uploader_buttons">
							<div id="progress" class="uploader_progress">
								<span>0%</span>
		    						<div class="bar">
		    				
	    							</div>
							</div>
						</div>
						<div class="dragarea_wrapper">
							<div class="dragarea">
								<span>Drag Files Here To Upload</span>
							</div>	
						</div>
						<div class="uploader_button input_wrapper">
							<span>Select Files</span>
							<input id="fileupload" class="uploader_button" type="file" name="files[]" multiple>
						</div>
						<span id="upload_start" class="uploader_button">Start Upload</span>
						<span id="upload_cancel" class="uploader_button">Cancel Upload</span>
						<span id="upload_back" class="uploader_button">Back</span>	
						<table id="upload_present"></table>		
			</div>
			<div id="weblink_wrapper">
						<div class="weblink_head">
							<h1>Insert Images Via URL</h1>
							<span id="weblink_beck" class="weblink_button">
								<span>Back</span>
								<img src="components/com_creativegallery/assets/images/icons/back.png" alt="back_icon">
							</span>
						</div>
						<div id="weblink_line_template" class="weblink_line" style="display:none">
							<div class="weblink_part">
								<label for="url">URL:</label>
								<input type="text" name="url">
							</div>
							<div class="weblink_part">
								<label for="name">Name:</label>
								<input type="text" name="name">
							</div>
							<div class="weblink_part">
								<span class="weblink_button weblink_add weblink_button_anim">
									<span>Add</span>
									<img src="components/com_creativegallery/assets/images/icons/save.png" alt="save_icon">
								</span>
								<span class="weblink_button weblink_more weblink_button_anim">
									<span>More</span>
									<img src="components/com_creativegallery/assets/images/icons/plus.png" alt="plus_icon">
								</span>
							</div>
						</div>
			</div>
		</div>
		<div id="confirm_dialog"><span>Are you sure that you want to delete selected rows?</span></div>
	</form>


</div>
<?php include (JPATH_BASE.'/components/com_creativegallery/helpers/footer.php'); 
	$document->addScript("components/com_creativegallery/assets/js/filemanager/filemanager.js");
	$document->addScript("components/com_creativegallery/assets/js/tagmanager/tagmanager.js");
	$document->addScript("components/com_creativegallery/assets/js/url_adder/url_adder.js");
	$document->addScript("components/com_creativegallery/assets/js/fileuploader/jqueryuploader.js");
	$document->addScript("components/com_creativegallery/assets/js/styles_interface/styles.js");
	$document->addScript("components/com_creativegallery/assets/js/add_album_main.js");

 ?>
<script>
	addAlbum (creativeSolutionsjQuery, <?php echo "'" . JURI::root(true) . "'" ?>);
</script>
<jdoc:include type="head" />