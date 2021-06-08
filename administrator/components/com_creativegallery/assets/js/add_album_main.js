function addAlbum ($, base_path) {
    slider_init = 0;
    $('div#cg_tabs').tabs({
        activate: function (event, ui) {
            ////console.log($(ui.newPanel).prop("id"));
            if ($(ui.newPanel).prop("id")=='tab2') {
                // $('div#tab2').tabs({
                slider_init = 1;  
                // }).show();
                viewPreview ();     
            } else if ($(ui.newPanel).prop("id")=='tab3') {
                slider_init = 2;  
                viewPreview (); 
            } else if ($(ui.newPanel).prop("id")=='tab4') {
                slider_init = 3;  
                viewPreview (); 
            }

        }
    }).show();
    
	$(document).bind('drop dragover', function (e) {
    	e.preventDefault();
	});

    

    
    var album_id = $("input[name='id']").val();
    // size conversion function

	function bytesToSize(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return '0 Bytes';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
	
    // Ajax Thumb Creation

    function loadXMLDoc (url, cfunc) {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=cfunc;
        xmlhttp.open ("GET", url, true);
        xmlhttp.send();
    }
    function getDirection (elem, event) {
        var position = $(elem).offset(),
            mouseX = parseInt(event.pageX),
            mouseY = parseInt(event.pageY),
            posLeft = parseInt(position.left),
            posTop = parseInt(position.top),
            posRight = posLeft + parseInt($(elem).width()),
            posBottom = posTop + parseInt($(elem).height()),
            offsetTop = Math.abs(posTop - mouseY),
            offsetLeft = Math.abs(posLeft - mouseX),
            offsetRight = Math.abs(posRight - mouseX),
            offsetBottom = Math.abs(posBottom - mouseY),
            min = Math.min(offsetTop, offsetLeft, offsetRight, offsetBottom);
            switch (min) {
                case offsetTop:
                    return "top";
                case offsetLeft:
                    return "left";
                case offsetRight:
                    return "right";
                case offsetBottom:
                    return "bottom";
            }
    }
    function directionAwareInit () {
        $("div#image_animated").on('mouseenter', function(event) {
            event.preventDefault();
            /* Act on the event */
            var direction = getDirection (this, event),
                animSpeed = $(this).css('transitionDuration');
            switch (direction) {
                case "top":
                    $(this).find("div.overlay").addClass('notransition').show().css({
                        top: 0 - $(this).height(),
                        left: 0
                    }).stop(true, true).animate({top: 0}, animSpeed, "swing",function(){
                    });
                    break;
                case "left":
                    $(this).find("div.overlay").addClass('notransition').show().css({
                        top: 0,
                        left: 0 - $(this).width()
                    }).stop(true, true).animate({left: 0}, animSpeed, "swing", function(){
                        // //console.log("left");
                    });
                    break;
                case "right":
                    $(this).find("div.overlay").addClass('notransition').show().css({
                        top: 0,
                        left: $(this).width()
                    }).stop(true, true).animate({left: 0}, animSpeed, "swing", function(){
                        // //console.log("right");
                    });
                    break;
                case "bottom":
                    $(this).find("div.overlay").addClass('notransition').show().css({
                        top: $(this).height(),
                        left: 0
                    }).stop(true, true).animate({top: 0}, animSpeed, "swing", function(){
                        // //console.log("bottom");
                    });
                    break;
            }
        });

            $("div#image_animated").on('mouseleave' , function(event) {
                event.preventDefault();
                /* Act on the event */
                var direction = getDirection (this, event),
                    animSpeed = $(this).css('transitionDuration');
                switch (direction) {
                    case "top":
                        $(this).find("div.overlay").addClass('notransition').css({
                            top: 0,
                            left: 0
                        }).stop(true, true).animate({top: 0-$(this).height()}, animSpeed, "swing", function(){
                            // //console.log("top");
                            $(this).hide();
                        });
                        break;
                    case "left":
                        $(this).find("div.overlay").addClass('notransition').css({
                            top: 0,
                            left: 0
                        }).stop(true, true).animate({left: 0 - $(this).width()}, animSpeed, "swing", function(){
                            // //console.log("left");
                            $(this).hide();
                        });
                        break;
                    case "right":
                        $(this).find("div.overlay").addClass('notransition').css({
                            top: 0,
                            left: 0
                        }).stop(true, true).animate({left: $(this).width()}, animSpeed, "swing", function(){
                            // //console.log("right");
                            $(this).hide();
                        });
                        break;
                    case "bottom":
                        $(this).find("div.overlay").addClass('notransition').css({
                            top: 0,
                            left: 0
                        }).stop(true, true).animate({top: $(this).height()}, animSpeed, "swing", function(){
                            // //console.log("bottom");
                            $(this).hide();
                        });
                        break;
                }

            });
    }
    function directionAwareStop () {
        $("div#image_animated").off('mouseenter').off('mouseleave');
    }
    function ajaxCreate (id, view, size) {
            view = view || "not";
            size = size || "not";
            var variables = "&id=" + id;
            if (view!="not") {
                variables = variables + "&mode=" + view;
            };
            if (size!="not") {
                variables = variables + "&size=" + size;
            };
            
            loadXMLDoc("../index.php?option=com_creativegallery&view=creativeajax&layout=thumbnailcreator&format=json" + variables, function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var response = JSON.parse(xmlhttp.responseText);
                ////console.log(response['status']);
                if (response['status']=="continue") {
                    ajaxCreate (id, view, size);
                } else if (response['status']=="done") {
                    
                }
            } 
        })
    }

    // filemanager open function
    function imgToTable(img, index) {
        var tr = document.createElement("TR"),
            td1 = document.createElement("TD"),
            td2 = document.createElement("TD"),
            td3 = document.createElement("TD"),
            td4 = document.createElement("TD"),
            td5 = document.createElement("TD"),
            td6 = document.createElement("TD"),
            td7 = document.createElement("TD"),
            input = document.createElement("INPUT"),
            reorder_img = document.createElement("IMG"),
            name_input = document.createElement("INPUT"),
            title_input = document.createElement("INPUT"),
            thumbnail_img = document.createElement("IMG"),
            description_textarea = document.createElement("TEXTAREA"),
            //tags_span = document.createElement("SPAN"),
            publish_img = document.createElement("IMG");
            input.type = "checkbox";
            td1.appendChild(input);
            td1.className = "table-select";
            reorder_img.src="components/com_creativegallery/assets/images/direction_vert.png";
            reorder_img.alt="reorder icon"
            td2.appendChild(reorder_img);
            td2.className = "table-reorder";
            tr.id="item_" + index;
        var img_name = $(img).find("span.name").text(),
            img_name_input = document.createElement("INPUT");
            img_name_input.type="hidden";
            img_name_input.value = $(img).data("path");
            img_name_input.name = "cg_post["+ "item_" + index + "][path]";
            thumbnail_img.src = $(img).find("img.icon_file").attr("src");
            thumbnail_img.alt = img_name;
            td3.appendChild(thumbnail_img);
            td3.appendChild(img_name_input);
            td3.className = "table-thumbnail";
            name_input.type="text";
            name_input.className = "cg_image_name";
            name_input.value = img_name;
            name_input.name = "cg_post["+ "item_" + index + "][name]";
            title_input.type="text";
            title_input.className = "cg_image_title";
            title_input.value = img_name;
            title_input.name = "cg_post["+ "item_" + index + "][title]";
            var name_span = document.createElement("span"),
                name_div = document.createElement("div");
            name_span.innerHTML="name:";
            name_div.appendChild(name_span);
            name_div.appendChild(name_input);
            var title_span = document.createElement("span"),
                title_div = document.createElement("div");
            title_span.innerHTML="title:";
            title_div.appendChild(title_span);
            title_div.appendChild(title_input);
            var link_input = document.createElement("INPUT"),
                link_div = document.createElement("DIV"),
                link_span = document.createElement("SPAN");
            link_span.innerHTML = "link(URL):";
            link_input.type="text";
            link_input.className="cg_image_link";
            link_input.value ="";
            link_input.name = "cg_post[item_" + index + "][link]";
            link_div.appendChild(link_span);
            link_div.appendChild(link_input);

            var target_select = document.createElement("SELECT"),
                target_div = document.createElement("DIV"),
                target_span = document.createElement("SPAN");
            target_span.innerHTML = "Target:";
            target_select.className="cg_image_select";
            target_select.value="0";
            target_select.name = "cg_post["+ "item_" + index + "][target]";
            var opt1 = document.createElement("OPTION"),
                opt2 = document.createElement("OPTION");
            opt1.value = "0";
            opt2.value = "1";
            opt1.innerHTML = "Same Page";
            opt2.innerHTML = "New Page";
            target_select.appendChild(opt1);
            target_select.appendChild(opt2);
            target_div.appendChild(target_span);
            target_div.appendChild(target_select);

            td4.appendChild(name_div);
            td4.appendChild(title_div);
            td4.appendChild(link_div);
            td4.appendChild(target_div);
            td4.className="table-image-name";
            description_textarea.innerHTML = "Image Description";
            description_textarea.className="table-image-description";
            description_textarea.name = "cg_post["+ "item_" + index + "][description]";
            td5.appendChild(description_textarea);
            td5.className = "table-title";
            //tags_span.innerHTML = "No Tags";
            var tags_input = document.createElement("input");
            tags_input.type = "hidden";
            tags_input.value = "";
            tags_input.name = "cg_post["+ "item_" + index + "][tags]";
            //td6.appendChild(tags_span);
            td6.appendChild(tags_input);
            td6.className = "table-image-tags";
            publish_img.src = "components/com_creativegallery/assets/images/icons/published.png";
            publish_img.title = "Published and Current";
            publish_img.alt = "publish icon";
            publish_img.className = "cg_publish_icon";
            var publish_img_input = document.createElement("INPUT");
            publish_img_input.type="hidden";
            publish_img_input.value = 1;
            publish_img_input.name = "cg_post["+ "item_" + index + "][publish]";
            td7.appendChild(publish_img);
            td7.appendChild(publish_img_input);
            td7.className = "table-published";
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(td4);
            tr.appendChild(td5);
            tr.appendChild(td6);
            tr.appendChild(td7);
            $("table.table-main>tbody").prepend(tr);
            indexCorrection ();
            tablecompanceate();
        }
    function indexCorrection () {
        var trArray = $("table.table-main>tbody").find("tr").toArray();
        var indexesArray = Array();
        for (var i = 0; i < trArray.length; i++) {
            $(trArray[i]).attr('id', 'item_' + i);
            $(trArray[i]).find('td.table-thumbnail').children('input').attr('name', 'cg_post[item_' + i + '][path]');
            $(trArray[i]).find('td.table-image-name').find('input.cg_image_name').attr('name', 'cg_post[item_' + i + '][name]');
            $(trArray[i]).find('td.table-image-name').find('input.cg_image_title').attr('name', 'cg_post[item_' + i + '][title]');
            $(trArray[i]).find('td.table-image-name').find('input.cg_image_link').attr('name', 'cg_post[item_' + i + '][link]');
            $(trArray[i]).find('td.table-image-name').find('select.cg_image_select').attr('name', 'cg_post[item_' + i + '][target]');
            $(trArray[i]).find('td.table-title').find('textarea').attr('name', 'cg_post[item_' + i + '][description]');
            $(trArray[i]).find('td.table-image-tags').find('input').attr('name', 'cg_post[item_' + i + '][tags]');
            $(trArray[i]).find('td.table-published').find('input').attr('name', 'cg_post[item_' + i + '][publish]');
            indexesArray.push ("item_" + i);
        };
        $("input#cg_album_ordering").val(indexesArray.join(" "));
    }
    function filemanagerOpen (currentDialog) {
		$("div#filemanager_dialog").dialog( {
            height : 600, 
            width : 1000, 
            modal: true, 
			show : { effect : "slideDown", duration : 400 }, 
            hide: { effect: "slideUp", duration: 400 },
			buttons : [ {
					text: "Ok",
					click: function() {
                        if (currentDialog == "Add Preview Image") {
                            var img = $("li.selected[data-type='file']").first();
                                imgDataPath = img.data("path");
                            if ( imgDataPath != null) {
                                // $("img#cg_preview_image").attr("src", img.find("img.icon_file").attr("src"));
                                $("img#cg_preview_image").attr("src", imgDataPath);
                                $("img#cg_preview_image").siblings('input').val(imgDataPath);

                            };
                            //////console.log(img.find("img.icon_file").attr("src"));
                        } else if (currentDialog == "Add Images") {
                            var imgs = $("li.selected[data-type='file']").toArray();
                            for (var i=0; i < imgs.length; i++) {
                                imgToTable(imgs[i], i);
                            }
                            
                           
                        }

                    var keys = $("table.table-main>tbody").find("tr").toArray();
                    var keys_id = Array();
                    for (var i = 0; i <= keys.length-1; i++) {
                        keys_id[i] = keys[i].id;        
                    };
                    //////console.log(keys_id.join(" "));
                    $("input#cg_album_ordering").val(keys_id.join(" "));
       	 			$( this ).dialog( "close" );
      			}
				}, {
					text: "Cancel",
					click: function() {
       	 			$( this ).dialog( "close" );
      			}
			}], 
			open: function (event, ui) {
					initFileManager($, base_path);
				},
            close: function (event, ui) {
                    deInitFileManager ($);
                }
			});

			$("div#filemanager_dialog").dialog("option", "title", currentDialog);
			$("div#filemanager_dialog").dialog( "open");
		}
	function tablecompanceate() {
        var bodyHeight = $('table.table-main').find('tbody').height()*1;

        var totalRowHeight = 0;
        $('table.table-main').find('tbody>tr').each(function(index, el) {
            totalRowHeight += $(el).height()*1;
        });
        if (totalRowHeight>bodyHeight) {
            $('table.table-main').find('thead').addClass('scroller');    
        }
        $('table.table-main').find('thead').off('scroll touchmove mousewheel').on('scroll touchmove mousewheel', function(e){
            e.preventDefault();
            e.stopPropagation();
            return false;
        })
    }
    function viewPreview () {
        if (slider_init==1) {
            var view = $('select#album_view').val(),
                thumbnail_size = parseInt($('div#cg_img_size_selector').slider("value")),
                //////// Images Options /////////////////
                images_margin = parseInt($('div#cg_img_margin_selector').slider("value")),
                images_border = parseInt($('input#cg_image_border_w_selector').spinner("value")),
                images_border_r = parseInt($('input#cg_image_border_r_selector').spinner("value")),
                images_border_color = $('input#image_border_colorpicker_input').val(),
                images_border_type = $('select#cg_image_border_t_selector').val(),
                ///////// 
                images_box_sh_h = parseInt($('input#cg_image_box_shadow_h_selector').spinner("value")),
                images_box_sh_v = parseInt($('input#cg_image_box_shadow_v_selector').spinner("value")),
                images_box_sh_blur = parseInt($('input#cg_image_box_shadow_b_selector').spinner("value")),
                images_box_sh_spread = parseInt($('input#cg_image_box_shadow_s_selector').spinner("value")),
                images_box_sh_type = $('select#cg_image_box_shadow_t_selector').val(),
                images_box_sh_color = $('input#image_box_shadow_colorpicker_input').val(),

                ///////// Container Options //////////////
                container_padding = parseInt($('input#cg_cont_p_selector').spinner("value")),
                container_bg_color = $('input#cg_bg_colorpicker_input').val(),
                container_border = parseInt($("input#cg_cont_border_w_selector").spinner("value")),
                container_border_r = parseInt($('input#cg_cont_border_r_selector').spinner("value")),
                container_border_type = $('select#cg_cont_border_t_selector').val(),
                container_border_color = $('input#cont_border_colorpicker_input').val(),
                container_box_sh_h = parseInt($('input#cg_cont_box_shadow_h_selector').spinner("value")),
                container_box_sh_v = parseInt($('input#cg_cont_box_shadow_v_selector').spinner("value")),
                container_box_sh_blur = parseInt($('input#cg_cont_box_shadow_b_selector').spinner("value")),
                container_box_sh_spread = parseInt($('input#cg_cont_box_shadow_s_selector').spinner("value")),
                container_box_sh_type = $('select#cg_cont_box_shadow_t_selector').val(),
                container_box_sh_color = $('input#cont_box_shadow_colorpicker_input').val(),
                // 
                txtsh_h = parseInt($('input#cg_text_shadow_h_selector').spinner("value")),
                txtsh_v = parseInt($('input#cg_text_shadow_v_selector').spinner("value")),
                txtsh_blur = parseInt($('input#cg_text_shadow_b_selector').spinner("value")),
                txtsh_color = $('input#text_shadow_colorpicker_input').val(),
                //
                txt_color = $("input#text_colorpicker_input").val(),
                txt_letter_spacing = $("input#cg_text_letter_spacing").spinner("value"),
                txt_word_spacing = $("input#cg_text_word_spacing").spinner("value"),
                txt_line_height = $("input#cg_text_line_height").spinner("value"),
                txt_size = $("input#cg_text_size").spinner("value"),
                txt_text_direction = $("select#cg_text_direction").val(),
                txt_unicode_bibi = $("select#cg_text_unicode_bibi").val(),
                txt_decoration = $("select#cg_text_decoration").val(),
                txt_transform = $("select#cg_text_transform").val(),

                seperator_width = parseInt($("input#seperator_border_w_selector").spinner("value")),
                seperator_margin = parseInt($('input#seperator_margin_selector').spinner("value")),
                seperator_type = $('select#seperator_border_t_selector').val(),
                seperator_color = $('input#seperator_border_colorpicker_input').val(),
                
                tags_enabled = $('select#tags_emabled').val(),

                img_border_type = "px";

                var output = $('div.preview_container'),
                    output_wrapper = output.find('div.cg_preview_container_wrapper').remove();


                if (tags_enabled == 0) {
                    $('div.tags_border_selector_wrapper').hide();
                    $('div.tags_box_shadow_selector_wrapper').hide();
                    $('div.tags_text_shadow_selector_wrapper').hide();
                    $('div.tags_text_options_selector_wrapper').hide();

                    $('div#tags_template').hide();
                    $('div#seperator_template_top').hide();
                } else {
                    $('div.tags_border_selector_wrapper').show();
                    $('div.tags_box_shadow_selector_wrapper').show();
                    $('div.tags_text_shadow_selector_wrapper').show();
                    $('div.tags_text_options_selector_wrapper').show();
                    var tags_margin = parseInt($('input#tags_m_selector').spinner("value")),
                        tags_padding_h = parseInt($('input#tags_p_h_selector').spinner("value")),
                        tags_padding_v = parseInt($('input#tags_p_v_selector').spinner("value")),
                        tags_bg_color = $('input#cg_tags_bg_colorpicker_input').val(),
                        
                        tags_border_width = parseInt($('input#cg_tags_border_w_selector').spinner("value")),
                        tags_border_radius = parseInt($('input#cg_tags_border_r_selector').spinner("value")),
                        tags_border_style = $('select#cg_tags_border_t_selector').val(),
                        tags_border_color = $('input#tags_border_colorpicker_input').val(),
                        
                        tags_boxsh_h = parseInt($('input#cg_tags_box_shadow_h_selector').spinner("value")),
                        tags_boxsh_v = parseInt($('input#cg_tags_box_shadow_v_selector').spinner("value")),
                        tags_boxsh_blur = parseInt($('input#cg_tags_box_shadow_b_selector').spinner("value")),
                        tags_boxsh_spread = parseInt($('input#cg_tags_box_shadow_s_selector').spinner("value")),
                        tags_boxsh_type = $('select#cg_tags_box_shadow_t_selector').val(),
                        tags_boxsh_color = $('input#tags_box_shadow_colorpicker_input').val(),
                        
                        tags_txtsh_v = parseInt($('input#cg_tags_text_shadow_h_selector').spinner("value")),
                        tags_txtsh_h = parseInt($('input#cg_tags_text_shadow_v_selector').spinner("value")),
                        tags_txtsh_blur = parseInt($('input#cg_tags_text_shadow_b_selector').spinner("value")),
                        tags_txtsh_color = $('input#tags_text_shadow_colorpicker_input').val(),
                        
                        tags_txt_letter_spacing = parseInt($('input#cg_tags_text_letter_spacing').spinner("value")),
                        tags_txt_word_spacing = parseInt($('input#cg_tags_text_word_spacing').spinner("value")),
                        tags_txt_line_height = parseInt($('input#cg_tags_text_line_height').spinner("value")),
                        tags_txt_color = $('input#tags_text_colorpicker_input').val(),
                        tags_txt_size = parseInt($('input#cg_tags_text_size').spinner("value")),
                        tags_txt_direction = $('select#cg_tags_text_direction').val(),
                        tags_txt_unicode_bibi = $('select#cg_tags_text_unicode_bibi').val(),
                        tags_txt_decoration = $('select#cg_tags_text_decoration').val(),
                        tags_txt_transform = $('select#cg_tags_text_transform').val();

                        ////console.log(tags_boxsh_color + " " + tags_boxsh_type + " " +  tags_boxsh_spread + " " + tags_boxsh_blur + " " + tags_boxsh_h + " " + tags_boxsh_v );

                        $('div#tags_template').show().find("span").css({
                            'margin': "0 " + tags_margin + "px",
                            'padding': tags_padding_h + "px " + tags_padding_v + "px",
                            'backgroundColor': tags_bg_color,
                            'color': tags_txt_color,
                            'borderWidth': tags_border_width + "px",
                            'borderStyle': tags_border_style,
                            'borderColor': tags_border_color,
                            'borderRadius': tags_border_radius + "px",
                            'boxShadow' : tags_boxsh_type + " " + tags_boxsh_h + "px " + tags_boxsh_v + "px " + tags_boxsh_blur + "px " + tags_boxsh_spread + "px " +  tags_boxsh_color,
                            'textShadow' : tags_txtsh_h + "px " + tags_txtsh_v + "px " + tags_txtsh_blur + "px " + tags_txtsh_color,
                            'letterSpacing' : tags_txt_letter_spacing,
                            'wordSpacing' : tags_txt_word_spacing,
                            'lineHeight' : tags_txt_line_height + "%",
                            'fontSize' : tags_txt_size,
                            'direction' : tags_txt_direction,
                            'unicodeBidi' : tags_txt_unicode_bibi,
                            'textDecoration' : tags_txt_decoration,
                            'textTransform' : tags_txt_transform
                        });
                        $('div#seperator_template_top').show();
                                                
                }       
                output.find('div#cg_preview_container_wrapper_template').css({
                    'borderWidth': container_border + "px",
                    'borderStyle': container_border_type,
                    'borderColor': container_border_color,
                    'borderRadius': container_border_r + "px",
                    'boxShadow' : container_box_sh_type + " " + container_box_sh_h + "px " + container_box_sh_v + "px " + container_box_sh_blur + "px " + container_box_sh_spread + "px " +  container_box_sh_color,
                    'backgroundColor' : container_bg_color,
                    'padding' : container_padding
                });

                output.find('div.seperator').css({
                    'width': '100%',
                    'height': 0,
                    'borderTopWidth': seperator_width + "px",
                    'borderStyle': seperator_type,
                    'borderColor': seperator_color,
                    'margin' : seperator_margin + "px 0"
                });
            if (view == 1) { //grid view
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width());    
                var count = Math.floor((container_w+images_margin)/(thumbnail_size+images_margin+images_border*2)),
                    leftMargin = Math.round((container_w - count*(thumbnail_size+images_border*2) - (count-1)*(images_margin))/2),
                    X=leftMargin, 
                    Y=0;
                    ////console.log(container_w);
                img_border_type = "%";
                for (var i = 0; i < count; i++) {
                    for (var j = 0; j < count; j++) {
                        var item = main_output.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').css({
                            'width': thumbnail_size + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': Y + "px",
                            "left": X + "px"
                        });
                        ////console.log(images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_type + " " + images_box_sh_color);
                        main_output.find('div.cg_preview').append(item);
                        X = X + thumbnail_size +images_margin + images_border*2;
                    }
                    X = leftMargin;
                    Y = Y + thumbnail_size + images_margin + images_border*2;
                };
                // //console.log(Y-images_margin);
                main_output.find('div.cg_preview').css({
                    "height": Y-images_margin+'px'
                });
                $('div.preview_container').height("auto");
            } else if (view == 2) { //Massionary H.
                $('div#cg_img_radius_selector').slider({'disabled': true});
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                img_border_type = "px";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width());    

                var count = 0,
                    leftMargin = 0,
                    currentX = Array(),
                    currentY = Array();
                if (images_margin==0) {
                    count = Math.floor((container_w)/(thumbnail_size+images_border));
                    leftMargin = Math.round((container_w - count*(thumbnail_size+images_border))/2);
                } else {
                    count = Math.floor((container_w+images_margin)/(thumbnail_size+images_margin+images_border*2));
                    leftMargin = Math.round((container_w- count*(thumbnail_size+images_border*2) - (count-1)*(images_margin))/2);
                }
                for (var i = 0; i < 20; i++) { 
                    var current_width = thumbnail_size,
                        current_height = thumbnail_size + parseInt(Math.random()*100);
                    //////console.log(current_width + " " + current_height);
                    var item = main_output.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('masionH').css({
                            'width': current_width + "px",
                            'height': current_height + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': 0 + "px",
                            "left": 0 + "px",
                            'opacity': 0
                    });
                    main_output.find('div.cg_preview').append(item);

                }
                 

                var objectsArray = main_output.find("div.masionH").toArray();
                for (var i = 1; i <= count; i++) {
                     if (i==1) {
                         currentX[i] = leftMargin;
                         currentY[i] = 0;
                     } else {
                         if (images_margin==0) {
                             currentX[i] = leftMargin + (i-1)*(thumbnail_size+images_border);
                         } else {
                             currentX[i] = leftMargin + (i-1)*(thumbnail_size+images_margin+images_border*2);
                         }
                         currentY[i] = 0;
                     }   
                };
                // ////console.log(leftMargin);
                // ////console.log(currentX);
                //////console.log(currentY);
                for (var i = 0; i < objectsArray.length; i++) {
                    // find the smallest row
                    var min = currentY[1];
                        min_pos = 1;
                    for (var j = 1; j <= count; j++) {
                        if (currentY[j]<min) 
                        {
                            min = currentY[j];
                            min_pos = j;
                        }
                    };
                    //////console.log(current_width + " " + current_height);
                     var current_width = thumbnail_size,
                         current_height = $(objectsArray[i]).height();
                    $(objectsArray[i]).css({
                            'width': current_width + "px",
                            'height': current_height + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': currentY[min_pos] + "px",
                            "left": currentX[min_pos] + "px",
                            opacity: 1
                    });
                    if (images_margin == 0) {
                        currentY[min_pos] = currentY[min_pos] + current_height + images_border;
                    } else {
                        currentY[min_pos] = currentY[min_pos] + current_height + images_margin + images_border*2;                 
                    }
                    
                };
                currentY.sort(function(a, b){return b-a});
                main_output.find('div.cg_preview').height(currentY[0] - images_margin);
                $('div.preview_container').height("auto");
            } else if (view == 3) { //Massionary V.
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                img_border_type = "px";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width());                 
                for (var i = 0; i < 20; i++) { 
                    var current_width = thumbnail_size + parseInt(Math.random()*parseInt(thumbnail_size/2)),
                        current_height = thumbnail_size;
                    //////console.log(current_width + " " + current_height);
                    var item = main_output.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('masionV').css({
                            'width': current_width + "px",
                            'height': current_height + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': 0 + "px",
                            "left": 0 + "px",
                            'opacity': 0
                    });
                    main_output.find('div.cg_preview').append(item);
                }
                var count = 1, //Math.floor((containerW+thumbnail_margin)/(thumbnail_size+thumbnail_margin+thumbnail_border*2)),
                currentY = 0,
                currentX = 0,
                exactW = 0,
                yCountPairs = Object(),
                yWidthPairs = Object(),
                objectsArray = main_output.find("div.masionV").toArray();

                for (var i = 0; i < objectsArray.length; i++) {
                    var current_width = parseInt($(objectsArray[i]).width()),
                        current_height = thumbnail_size;
                    $(objectsArray[i]).css({
                        'width': current_width + "px",
                        'height': current_height + "px",
                        'borderWidth': images_border + "px",
                        'borderStyle': images_border_type,
                        'borderColor': images_border_color,
                        'borderRadius': images_border_r + img_border_type,
                        'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                        'top': currentY + "px",
                        "left": currentX + "px",
                        'opacity': 1
                    });
                    exactW = exactW + current_width + images_border*2 + images_margin;
                    currentX = currentX + current_width + images_border*2 + images_margin;
                    count ++ ;
                    if (exactW>=(container_w)) {
                        yCountPairs[currentY] = count-1;
                        yWidthPairs[currentY] = exactW;
                        currentX = 0;
                        exactW = 0;
                        currentY = currentY + current_height + images_border*2 +images_margin;
                        count = 1;
                    } 
                    if (i == objectsArray.length-1) {
                        yCountPairs[currentY] = count-1;
                        yWidthPairs[currentY] = 0;
                    };              
                    //if ($(objectsArray[i]).children) {};
                    //leftMargin = Math.round((containerW - count*(thumbnail_size+thumbnail_border*2) - (count-1)*(thumbnail_margin))/2),
                };
                var count = 0,
                    newX = 0;
                for (var i = 0; i < objectsArray.length; i++) {

                    var currentY = parseInt($(objectsArray[i]).css('top')),
                        currentX = parseInt($(objectsArray[i]).css('left')),
                        currentW = parseInt($(objectsArray[i]).css('width')),
                        currentMargin = (parseInt(yWidthPairs[currentY])-container_w-images_border*2)/(yWidthPairs[currentY]-((yCountPairs[currentY]-1)*(images_margin)));
                        // Search for too narow cell


                        //////console.log(currentMargin);   
                    //////console.log(yWidthPairs[currentY]);               
                    if (yWidthPairs[currentY]==0) {
                        //yWidthPairs[currentY] = 0;
                        continue;
                    };

                    //////console.log(currentMargin);
                    var newVal = Math.round(( (currentW + images_border*2 )* currentMargin)),
                        newW = currentW - newVal,
                        newM = Math.round(newVal/2);
                    if ((currentW - newW) <= 1) {
                        //////console.log(currentW + " " +  newW);
                        newW = currentW;
                        newM = 0;
                    };
                    $(objectsArray[i]).css({
                        'left' : newX
                    }).width(newW + "px");
                    //////console.log(Math.round(thumbnail_margin*currentMargin));
                    newX = newX + newW + images_margin + images_border*2;
                    count ++;
                    if (count == yCountPairs[currentY]) {
                        yWidthPairs[currentY] = newX - images_margin;
                        newX = 0;
                        count = 0;
                    } 
                };
                count = 0;
                for (var i = 0; i < objectsArray.length; i++) {
                    var currentY = parseInt($(objectsArray[i]).css('top')),
                        currentX = parseInt($(objectsArray[i]).css('left')),
                        currentW = parseInt($(objectsArray[i]).css('width')),
                        currentMargin = Math.round((parseInt(container_w-yWidthPairs[currentY]))/(yCountPairs[currentY]-1));
                        // Search for too narow cell
                        //////console.log(currentMargin);   
                    if (yWidthPairs[currentY] == 0) {
                        continue;
                    };

                    var newVal = currentX + count * currentMargin;
                    if (count == yCountPairs[currentY]-1) {
                        newVal = container_w - currentW - images_border*2;
                    } 
                    $(objectsArray[i]).css({
                        'left' : newVal
                    });
                    count ++;
                    if (count == yCountPairs[currentY]) {
                        count = 0;
                    } 
                };
                
                main_output.find('div.cg_preview').height(currentY + images_border + thumbnail_size);
                $('div.preview_container').height("auto");
            } else if (view == 4) { // Slider H.
                $('div#cg_preview_height').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div#cg_preview_speed').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                img_border_type = "px";
                var container = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                output.append(container);
                main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width()),
                    preview_height = parseInt($('div#cg_preview_height').slider('value'));
                var preview = main_output.find('div.cg_preview').clone().addClass('main_preview');
                main_output.append(preview);
                var main_preview = main_output.find('div.main_preview'),
                    preview_image = main_preview.find("div#cg_image_preview_template").clone().removeAttr("id").addClass('cg_image_preview_item').css({
                        'width': container_w + 'px',
                        'height': preview_height + "px"
                    });
                main_preview.append(preview_image).height(preview_height);

                var seperator = main_output.find('div.cg_preview').first().clone().addClass('seperator');
                seperator.css({
                    'width': '100%',
                    'height': 0,
                    'borderTopWidth': seperator_width + "px",
                    'borderStyle': seperator_type,
                    'borderColor': seperator_color,
                    'margin' : seperator_margin + "px 0"
                });
                
                main_output.append(seperator);
                
                var main_images_section = main_output.find('div.cg_preview').first().clone().addClass('main-images');
                main_output.append(main_images_section);
                main_images_section = main_output.find('div.main-images');
                
                var count = Math.floor((container_w+images_margin)/(thumbnail_size+images_margin+images_border*2)),
                    X=0, 
                    Y=0;
                for (var i = 0; i < count; i++) {
                    currentWidth = thumbnail_size + parseInt(Math.random()*100);
                    var item = main_images_section.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').css({
                            'width': currentWidth + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': Y + "px",
                            "left": X + "px"
                    });
                    var Xprev = X;
                    if (images_margin==0) {
                        X = X + currentWidth + images_border;
                    } else {
                        X = X + currentWidth + images_margin + 2*images_border;
                    }
                    if (X - images_margin >= container_w) {
                        item.css('width', (container_w - Xprev - 2*images_border + "px") );
                    }
                    main_images_section.append(item);
                }
                main_images_section.height(thumbnail_size + 2*images_border+ "px");
                
                //+ 2*seperator_margin+seperator_width + 
                $('div.preview_container').height('auto');
            } else if (view == 5) { // Slider V.
                $('div#cg_preview_height').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div#cg_preview_speed').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                img_border_type = "px";
                var container = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                output.append(container);
                main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width()),
                    preview_height = parseInt($('div#cg_preview_height').slider('value'));
                var preview = main_output.find('div.cg_preview').clone().addClass('main_preview');
                main_output.append(preview);
                var main_preview = main_output.find('div.main_preview'),
                    preview_image = main_preview.find("div#cg_image_preview_template").clone().removeAttr("id").addClass('cg_image_preview_item').css({
                        'width': container_w - seperator_width - seperator_margin*2 - thumbnail_size - images_border*2 + 'px',
                        'height': preview_height + "px",
                    });
                main_preview.append(preview_image).height(preview_height).width(container_w - seperator_width - seperator_margin*2 - thumbnail_size - images_border*2 + 'px').css("float", 'left');

                var seperator = main_output.find('div.cg_preview').first().clone().addClass('seperator');
                seperator.css({
                    'width': 0,
                    'height': preview_height,
                    'borderLeftWidth': seperator_width + "px",
                    'borderStyle': seperator_type,
                    'borderColor': seperator_color,
                    'margin' : "0 " + seperator_margin + "px",
                    'float' : 'left'
                });
                
                //console.log(container_w - seperator_width - seperator_margin*2 - thumbnail_size - images_border*2 + 'px');
                //console.log(seperator_width + " " + seperator_margin);

                main_output.append(seperator);
                
                var main_images_section = main_output.find('div.cg_preview').first().clone().addClass('main-images');
                main_output.append(main_images_section);
                main_images_section = main_output.find('div.main-images');
                
                var count = Math.floor((preview_height+images_margin)/(thumbnail_size+images_margin+images_border*2)),
                    X=0, 
                    Y=0;
                    if (count==0) {
                        count =1;
                    }
                for (var i = 0; i < count; i++) {
                    currentHeight = thumbnail_size + parseInt(Math.random()*100);
                    var item = main_images_section.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').css({
                            'width': thumbnail_size + "px",
                            'height': currentHeight + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': Y + "px",
                            "left": X + "px"
                    });
                    var Yprev = Y;
                    if (images_margin==0) {
                        Y = Y + currentHeight + images_border;
                    } else {
                        Y = Y + currentHeight + images_margin + 2*images_border;
                    }
                    if (Y - images_margin >= preview_height) {
                        item.css('height', (preview_height - Yprev - 2*images_border + "px") );
                    }
                    main_images_section.append(item);
                }
                //console.log(thumbnail_size + 2*images_border - 1 + "px");
                main_images_section.width(thumbnail_size + 2*images_border - 1 + "px").css("float", "left");
                var clear = main_output.find('div.cg_preview').first().clone().addClass('clear').css("clear", "both");
                main_output.append(clear);
                //+ 2*seperator_margin+seperator_width + 
                main_output.height('auto');
                $('div.preview_container').height('auto');
            } else if (view == 6) { // Carousel Classic
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div#cg_img_size_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div.main_text_shadow_selector_wrapper').hide();
                $('div.main_text_options_selector_wrapper').hide();
                img_border_type = "%";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    main_output = output.find("div.cg_preview_container_wrapper");
                var container_w  = parseInt(main_output.width());   

                var thumbnail_count = $('div#cg_img_count_selector').slider('value'),
                    thumbnail_size = (container_w-40-(images_border*2*thumbnail_count)-(thumbnail_count-1)*images_margin)/thumbnail_count;
                var X = Math.round((container_w - thumbnail_count*(thumbnail_size+images_border*2) - (thumbnail_count-1)*(images_margin))/2),
                    Y = 0;
                for (var i = 0; i < thumbnail_count; i++) { 
                    var current_width = thumbnail_size ;
                        current_height = thumbnail_size ;
                    
                    var item = main_output.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').css({
                            'width': current_width + "px",
                            'height': current_height + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'top': Y + "px",
                            "left": X + "px"
                    });
                    main_output.find('div.cg_preview').append(item);
                    if (images_margin==0) {
                        X = X + current_width + images_border
                    } else {
                        X = X + current_width + images_border*2 + images_margin;    
                    }
                    
                }
                

                var hint = main_output.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').css({
                            'width': 200 + "px",
                            'height': 100 + "px",
                            //'borderWidth': images_border + "px",
                            //'borderStyle': images_border_type,
                            //'borderColor': images_border_color,
                            'borderRadius': 5 + "%",
                            'top': thumbnail_size + 2*Y + 50 + "px",
                            "left": (container_w - 200)/2 + "px"
                    });
                hint.append("<span class='hint'>Thumbnails size can be changed from preview depending on your container size</span>");
                main_output.find('div.cg_preview').append(hint);
                main_output.find("div.cg_preview").height(thumbnail_size + 2*Y  + 150 + "px");
                $('div.preview_container').height('auto');
            } else if (view == 7) { // Blog Left
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').show();
                $('div.main_text_options_selector_wrapper').show();
                img_border_type = "%";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    
                for (var i = 0; i < 3; i++) { 
                    var main_output = output.find("div.cg_preview_container_wrapper"),
                        container_w  = parseInt(main_output.width()),
                        current_container = main_output.find('div.cg_preview').clone().removeClass('cg_preview').addClass('cg_preview_blog');
                        main_output.append(current_container);
                        current_container = main_output.find("div.cg_preview_blog").last();


                    var img = current_container.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('cg_image_preview_img').css({
                            'width': thumbnail_size + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'position': "static",
                            "float":"left",
                            'margin': images_margin + "px",
                            'backgroundColor': "rgb(221, 221, 221)"
                    });
                    current_container.append(img);
                    current_container.append("<span class='description'>Turned it up should no valley cousin he. Speaking numerous ask did horrible packages set. Ashamed herself has distant can studied mrs. Led therefore its middleton perpetual fulfilled provision frankness. Small he drawn after among every three no. All having but you edward genius though remark one. Greatly cottage thought fortune no mention he. Of mr certainty arranging am smallness by conveying. Him plate you allow built grave. Sigh sang nay sex high yet door game. She dissimilar was favourable unreserved nay expression contrasted saw. Past her find she like bore pain open. Shy lose need eyes son not shot. Jennings removing are his eat dashwood. Middleton as pretended listening he smallness perceived. Now his but two green spoil drift. Arrival entered an if drawing request. How daughters not promotion few knowledge contented. Yet winter law behind number stairs garret excuse. Minuter we natural conduct gravity if pointed oh no. Am immediate unwilling of attempted admitting disposing it. Handsome opinions on am at it ladyship. Civility vicinity graceful is it at. Improve up at to on mention perhaps raising. Way building not get formerly her peculiar. Up uncommonly prosperous sentiments simplicity acceptance to so. Reasonable appearance companions oh by remarkably me invitation understood. Pursuit elderly ask perhaps all. </span>");
                    current_container.find('span.description').css({
                        'textShadow' : txtsh_h + "px " + txtsh_v + "px " + txtsh_blur + "px " + txtsh_color,
                        'color' : txt_color,
                        'letterSpacing' : txt_letter_spacing,
                        'wordSpacing' : txt_word_spacing,
                        'lineHeight' : txt_line_height + "%",
                        'fontSize' : txt_size,
                        'direction' : txt_text_direction,
                        'unicodeBidi' : txt_unicode_bibi,
                        'textDecoration' : txt_decoration,
                        'textTransform' : txt_transform
                    });
                    if (i<2) {
                        var seperator = main_output.find('div#seperator_template_top').clone().show().removeAttr('id');
                        current_container.append(seperator);    
                    };
                    
                }
                $('div.preview_container').height("auto");
            } else if (view == 8) {
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').show();
                $('div.main_text_options_selector_wrapper').show();
                img_border_type = "%";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    
                for (var i = 0; i < 3; i++) { 
                    var main_output = output.find("div.cg_preview_container_wrapper"),
                        container_w  = parseInt(main_output.width()),
                        current_container = main_output.find('div.cg_preview').clone().removeClass('cg_preview').addClass('cg_preview_blog');
                        main_output.append(current_container);
                        current_container = main_output.find("div.cg_preview_blog").last();


                    var img = current_container.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('cg_image_preview_img').css({
                            'width': thumbnail_size + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'position': "static",
                            "float":"right",
                            'margin': images_margin + "px",
                            'backgroundColor': "rgb(221, 221, 221)"
                    });
                    current_container.append(img);
                    current_container.append("<span class='description'>Turned it up should no valley cousin he. Speaking numerous ask did horrible packages set. Ashamed herself has distant can studied mrs. Led therefore its middleton perpetual fulfilled provision frankness. Small he drawn after among every three no. All having but you edward genius though remark one. Greatly cottage thought fortune no mention he. Of mr certainty arranging am smallness by conveying. Him plate you allow built grave. Sigh sang nay sex high yet door game. She dissimilar was favourable unreserved nay expression contrasted saw. Past her find she like bore pain open. Shy lose need eyes son not shot. Jennings removing are his eat dashwood. Middleton as pretended listening he smallness perceived. Now his but two green spoil drift. Arrival entered an if drawing request. How daughters not promotion few knowledge contented. Yet winter law behind number stairs garret excuse. Minuter we natural conduct gravity if pointed oh no. Am immediate unwilling of attempted admitting disposing it. Handsome opinions on am at it ladyship. Civility vicinity graceful is it at. Improve up at to on mention perhaps raising. Way building not get formerly her peculiar. Up uncommonly prosperous sentiments simplicity acceptance to so. Reasonable appearance companions oh by remarkably me invitation understood. Pursuit elderly ask perhaps all. </span>");
                    current_container.find('span.description').css({
                        'textShadow' : txtsh_h + "px " + txtsh_v + "px " + txtsh_blur + "px " + txtsh_color,
                        'color' : txt_color,
                        'letterSpacing' : txt_letter_spacing,
                        'wordSpacing' : txt_word_spacing,
                        'lineHeight' : txt_line_height + "%",
                        'fontSize' : txt_size,
                        'direction' : txt_text_direction,
                        'unicodeBidi' : txt_unicode_bibi,
                        'textDecoration' : txt_decoration,
                        'textTransform' : txt_transform
                    });
                    if (i<2) {
                        var seperator = main_output.find('div#seperator_template_top').clone().show().removeAttr('id');
                        current_container.append(seperator);    
                    };
                }
                $('div.preview_container').height("auto");
            } else if (view == 9) {
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').show();
                $('div.main_text_options_selector_wrapper').show();
                img_border_type = "%";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    
                for (var i = 0; i < 3; i++) { 
                    var main_output = output.find("div.cg_preview_container_wrapper"),
                        container_w  = parseInt(main_output.width()),
                        current_container = main_output.find('div.cg_preview').clone().removeClass('cg_preview').addClass('cg_preview_blog');
                        main_output.append(current_container);
                        current_container = main_output.find("div.cg_preview_blog").last();
                    var floater = "";
                    if (i==0) {
                        floater = "left";
                    } else if(i==1) {
                        floater = "right";
                    } else {
                        floater = "left";
                    }

                    var img = current_container.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('cg_image_preview_img').css({
                            'width': thumbnail_size + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'position': "static",
                            "float": floater,
                            'margin': images_margin + "px",
                            'backgroundColor': "rgb(221, 221, 221)"
                    });
                    current_container.append(img);
                    current_container.append("<span class='description'>Turned it up should no valley cousin he. Speaking numerous ask did horrible packages set. Ashamed herself has distant can studied mrs. Led therefore its middleton perpetual fulfilled provision frankness. Small he drawn after among every three no. All having but you edward genius though remark one. Greatly cottage thought fortune no mention he. Of mr certainty arranging am smallness by conveying. Him plate you allow built grave. Sigh sang nay sex high yet door game. She dissimilar was favourable unreserved nay expression contrasted saw. Past her find she like bore pain open. Shy lose need eyes son not shot. Jennings removing are his eat dashwood. Middleton as pretended listening he smallness perceived. Now his but two green spoil drift. Arrival entered an if drawing request. How daughters not promotion few knowledge contented. Yet winter law behind number stairs garret excuse. Minuter we natural conduct gravity if pointed oh no. Am immediate unwilling of attempted admitting disposing it. Handsome opinions on am at it ladyship. Civility vicinity graceful is it at. Improve up at to on mention perhaps raising. Way building not get formerly her peculiar. Up uncommonly prosperous sentiments simplicity acceptance to so. Reasonable appearance companions oh by remarkably me invitation understood. Pursuit elderly ask perhaps all. </span>");
                    current_container.find('span.description').css({
                        'textShadow' : txtsh_h + "px " + txtsh_v + "px " + txtsh_blur + "px " + txtsh_color,
                        'color' : txt_color,
                        'letterSpacing' : txt_letter_spacing,
                        'wordSpacing' : txt_word_spacing,
                        'lineHeight' : txt_line_height + "%",
                        'fontSize' : txt_size,
                        'direction' : txt_text_direction,
                        'unicodeBidi' : txt_unicode_bibi,
                        'textDecoration' : txt_decoration,
                        'textTransform' : txt_transform
                    });
                    if (i<2) {
                        var seperator = main_output.find('div#seperator_template_top').clone().show().removeAttr('id');
                        current_container.append(seperator);    
                    };
                } 
                $('div.preview_container').height("auto");
            } else if (view == 10) {
                $('div#cg_preview_height').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_preview_speed').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_count_selector').slider({'disabled': true}).parents('div.option_wrapper').hide();
                $('div#cg_img_size_selector').slider({'disabled': false}).parents('div.option_wrapper').show();
                $('div.main_text_shadow_selector_wrapper').show();
                $('div.main_text_options_selector_wrapper').show();
                img_border_type = "%";
                var main_output = output.find('div#cg_preview_container_wrapper_template').clone().removeAttr('id').show().addClass('cg_preview_container_wrapper');
                    output.append(main_output);
                    
                for (var i = 0; i < 3; i++) { 
                    var main_output = output.find("div.cg_preview_container_wrapper"),
                        container_w  = parseInt(main_output.width()),
                        current_container = main_output.find('div.cg_preview').clone().removeClass('cg_preview').addClass('cg_preview_blog');
                        main_output.append(current_container);
                        current_container = main_output.find("div.cg_preview_blog").last();
                    var img = current_container.find('div#cg_image_preview_template').clone().removeAttr('id').addClass('cg_image_preview_item').addClass('cg_image_preview_img').css({
                            'width': thumbnail_size + "px",
                            'height': thumbnail_size + "px",
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color,
                            'borderRadius': images_border_r + img_border_type,
                            'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color,
                            'position': "static",
                            'margin': images_margin + "px auto",
                            'backgroundColor': "rgb(221, 221, 221)"
                    });
                    current_container.append(img);
                    if (i<2) {
                        var seperator = main_output.find('div#seperator_template_top').clone().show().removeAttr('id');
                        current_container.append(seperator);    
                    };
                } 
                $('div.preview_container').height("auto");
            } 
            $('input#cg_image_border_r_type_selector').val(img_border_type);
            ////console.log(img_border_type);
        } else if(slider_init==2){

            var container_bg_color = $('input#cg_bg_colorpicker_input').val(),
                tags_margin = parseInt($('input#tags_m_selector').spinner("value")),
                tags_padding_h = parseInt($('input#tags_p_h_selector').spinner("value")),
                tags_padding_v = parseInt($('input#tags_p_v_selector').spinner("value")),
                tags_bg_color = $('input#cg_tags_bg_colorpicker_input').val(),
                tags_border_width = parseInt($('input#cg_tags_border_w_selector').spinner("value")),
                tags_border_radius = parseInt($('input#cg_tags_border_r_selector').spinner("value")),
                tags_border_style = $('select#cg_tags_border_t_selector').val(),
                tags_border_color = $('input#tags_border_colorpicker_input').val(),
                        
                tags_boxsh_h = parseInt($('input#cg_tags_box_shadow_h_selector').spinner("value")),
                tags_boxsh_v = parseInt($('input#cg_tags_box_shadow_v_selector').spinner("value")),
                tags_boxsh_blur = parseInt($('input#cg_tags_box_shadow_b_selector').spinner("value")),
                tags_boxsh_spread = parseInt($('input#cg_tags_box_shadow_s_selector').spinner("value")),
                tags_boxsh_type = $('select#cg_tags_box_shadow_t_selector').val(),
                tags_boxsh_color = $('input#tags_box_shadow_colorpicker_input').val(),
                        
                tags_txtsh_v = parseInt($('input#cg_tags_text_shadow_h_selector').spinner("value")),
                tags_txtsh_h = parseInt($('input#cg_tags_text_shadow_v_selector').spinner("value")),
                tags_txtsh_blur = parseInt($('input#cg_tags_text_shadow_b_selector').spinner("value")),
                tags_txtsh_color = $('input#tags_text_shadow_colorpicker_input').val(),
                        
                tags_txt_letter_spacing = parseInt($('input#cg_tags_text_letter_spacing').spinner("value")),
                tags_txt_word_spacing = parseInt($('input#cg_tags_text_word_spacing').spinner("value")),
                tags_txt_line_height = parseInt($('input#cg_tags_text_line_height').spinner("value")),
                tags_txt_color = $('input#tags_text_colorpicker_input').val(),
                tags_txt_size = parseInt($('input#cg_tags_text_size').spinner("value")),
                tags_txt_direction = $('select#cg_tags_text_direction').val(),
                tags_txt_unicode_bibi = $('select#cg_tags_text_unicode_bibi').val(),
                tags_txt_decoration = $('select#cg_tags_text_decoration').val(),
                tags_txt_transform = $('select#cg_tags_text_transform').val(),

                //

                tags_margin_hover = parseInt($('input#tags_m_selector_hover').spinner("value")),
                tags_padding_h_hover = parseInt($('input#tags_p_h_selector_hover').spinner("value")),
                tags_padding_v_hover = parseInt($('input#tags_p_v_selector_hover').spinner("value")),
                tags_bg_color_hover = $('input#cg_tags_bg_colorpicker_input_hover').val(),
                tags_border_width_hover = parseInt($('input#cg_tags_border_w_selector_hover').spinner("value")),
                tags_border_radius_hover = parseInt($('input#cg_tags_border_r_selector_hover').spinner("value")),
                tags_border_style_hover = $('select#cg_tags_border_t_selector_hover').val(),
                tags_border_color_hover = $('input#tags_border_colorpicker_input_hover').val(),
                        
                tags_boxsh_h_hover = parseInt($('input#cg_tags_box_shadow_h_selector_hover').spinner("value")),
                tags_boxsh_v_hover = parseInt($('input#cg_tags_box_shadow_v_selector_hover').spinner("value")),
                tags_boxsh_blur_hover = parseInt($('input#cg_tags_box_shadow_b_selector_hover').spinner("value")),
                tags_boxsh_spread_hover = parseInt($('input#cg_tags_box_shadow_s_selector_hover').spinner("value")),
                tags_boxsh_type_hover = $('select#cg_tags_box_shadow_t_selector_hover').val(),
                tags_boxsh_color_hover = $('input#tags_box_shadow_colorpicker_input_hover').val(),
                        

                tags_txtsh_v_hover = parseInt($('input#cg_tags_text_shadow_h_selector_hover').spinner("value")),
                tags_txtsh_h_hover = parseInt($('input#cg_tags_text_shadow_v_selector_hover').spinner("value")),
                tags_txtsh_blur_hover = parseInt($('input#cg_tags_text_shadow_b_selector_hover').spinner("value")),
                tags_txtsh_color_hover = $('input#tags_text_shadow_colorpicker_input_hover').val(),
                        
                tags_txt_letter_spacing_hover = parseInt($('input#cg_tags_text_letter_spacing_hover').spinner("value")),
                tags_txt_word_spacing_hover = parseInt($('input#cg_tags_text_word_spacing_hover').spinner("value")),
                tags_txt_line_height_hover = parseInt($('input#cg_tags_text_line_height_hover').spinner("value")),
                tags_txt_color_hover = $('input#tags_text_colorpicker_input_hover').val(),
                tags_txt_size_hover = parseInt($('input#cg_tags_text_size_hover').spinner("value")),
                tags_txt_direction_hover = $('select#cg_tags_text_direction_hover').val(),
                tags_txt_unicode_bibi_hover = $('select#cg_tags_text_unicode_bibi_hover').val(),
                tags_txt_decoration_hover = $('select#cg_tags_text_decoration_hover').val(),
                tags_txt_transform_hover = $('select#cg_tags_text_transform_hover').val(),

                anim_speed = $("div#cg_tags_anim_speed").slider("value");

                ////console.log(tags_boxsh_h_hover + " " + tags_boxsh_v_hover + " " + tags_boxsh_blur_hover + " "+ tags_boxsh_spread_hover + " " + tags_boxsh_type_hover + " " + tags_boxsh_color_hover);
                ////console.log(anim_speed);
                $('div.preview_container_tags').find("div#tags_template_normal").find("span").css({
                    'margin': "0 " + tags_margin + "px",
                    'padding': tags_padding_h + "px " + tags_padding_v + "px",
                    'backgroundColor': tags_bg_color,
                    'color': tags_txt_color,
                    'borderWidth': tags_border_width + "px",
                    'borderStyle': tags_border_style,
                    'borderColor': tags_border_color,
                    'borderRadius': tags_border_radius + "px",
                    'boxShadow' : tags_boxsh_type + " " + tags_boxsh_h + "px " + tags_boxsh_v + "px " + tags_boxsh_blur + "px " + tags_boxsh_spread + "px " +  tags_boxsh_color,
                    'textShadow' : tags_txtsh_h + "px " + tags_txtsh_v + "px " + tags_txtsh_blur + "px " + tags_txtsh_color,
                    'letterSpacing' : tags_txt_letter_spacing,
                    'wordSpacing' : tags_txt_word_spacing,
                    'lineHeight' : tags_txt_line_height + "%",
                    'fontSize' : tags_txt_size,
                    'direction' : tags_txt_direction,
                    'unicodeBidi' : tags_txt_unicode_bibi,
                    'textDecoration' : tags_txt_decoration,
                    'textTransform' : tags_txt_transform
                });

                $('div.preview_container_tags').find("div#tags_template_hover").find("span").css({
                    'margin': "0 " + tags_margin + "px",
                    'padding': tags_padding_h + "px " + tags_padding_v + "px",
                    'backgroundColor': tags_bg_color_hover,
                    'color': tags_txt_color_hover,
                    'borderWidth': tags_border_width + "px",
                    'borderStyle': tags_border_style,
                    'borderColor': tags_border_color_hover,
                    'borderRadius': tags_border_radius_hover + "px",
                    'boxShadow' : tags_boxsh_type_hover + " " + tags_boxsh_h_hover + "px " + tags_boxsh_v_hover + "px " + tags_boxsh_blur_hover + "px " + tags_boxsh_spread_hover + "px " +  tags_boxsh_color_hover,
                    'textShadow' : tags_txtsh_h_hover + "px " + tags_txtsh_v_hover + "px " + tags_txtsh_blur_hover + "px " + tags_txtsh_color_hover,
                    'letterSpacing' : tags_txt_letter_spacing,
                    'wordSpacing' : tags_txt_word_spacing,
                    'lineHeight' : tags_txt_line_height + "%",
                    'fontSize' : tags_txt_size,
                    'direction' : tags_txt_direction,
                    'unicodeBidi' : tags_txt_unicode_bibi,
                    'textDecoration' : tags_txt_decoration,
                    'textTransform' : tags_txt_transform
                });
                $('div.preview_container_tags').find('div.tags_container').css({
                    backgroundColor : container_bg_color
                })
                $('div.preview_container_tags').find("div#tags_template_animated").find("span").css({
                    'margin': "0 " + tags_margin + "px",
                    'padding': tags_padding_h + "px " + tags_padding_v + "px",
                    'backgroundColor': tags_bg_color,
                    'color': tags_txt_color,
                    'borderWidth': tags_border_width + "px",
                    'borderStyle': tags_border_style,
                    'borderColor': tags_border_color,
                    'borderRadius': tags_border_radius + "px",
                    'boxShadow' : tags_boxsh_type + " " + tags_boxsh_h + "px " + tags_boxsh_v + "px " + tags_boxsh_blur + "px " + tags_boxsh_spread + "px " +  tags_boxsh_color,
                    'textShadow' : tags_txtsh_h + "px " + tags_txtsh_v + "px " + tags_txtsh_blur + "px " + tags_txtsh_color,
                    'letterSpacing' : tags_txt_letter_spacing,
                    'wordSpacing' : tags_txt_word_spacing,
                    'lineHeight' : tags_txt_line_height + "%",
                    'fontSize' : tags_txt_size,
                    'direction' : tags_txt_direction,
                    'unicodeBidi' : tags_txt_unicode_bibi,
                    'textDecoration' : tags_txt_decoration,
                    'textTransform' : tags_txt_transform,
                    'transition' : 'all ' + anim_speed/1000 + "s"
                });
                
                $('div.preview_container_tags').find("div#tags_template_animated").find("span").hover(function() {
                    $(this).css({
                    'margin': "0 " + tags_margin + "px",
                    'padding': tags_padding_h + "px " + tags_padding_v + "px",
                    'backgroundColor': tags_bg_color_hover,
                    'color': tags_txt_color_hover,
                    'borderWidth': tags_border_width + "px",
                    'borderStyle': tags_border_style,
                    'borderColor': tags_border_color_hover,
                    'borderRadius': tags_border_radius_hover + "px",
                    'boxShadow' : tags_boxsh_type_hover + " " + tags_boxsh_h_hover + "px " + tags_boxsh_v_hover + "px " + tags_boxsh_blur_hover + "px " + tags_boxsh_spread_hover + "px " +  tags_boxsh_color_hover,
                    'textShadow' : tags_txtsh_h_hover + "px " + tags_txtsh_v_hover + "px " + tags_txtsh_blur_hover + "px " + tags_txtsh_color_hover,
                    'letterSpacing' : tags_txt_letter_spacing,
                    'wordSpacing' : tags_txt_word_spacing,
                    'lineHeight' : tags_txt_line_height + "%",
                    'fontSize' : tags_txt_size,
                    'direction' : tags_txt_direction,
                    'unicodeBidi' : tags_txt_unicode_bibi,
                    'textDecoration' : tags_txt_decoration,
                    'textTransform' : tags_txt_transform
                });
                }, function() {
                    $(this).css({
                    'margin': "0 " + tags_margin + "px",
                    'padding': tags_padding_h + "px " + tags_padding_v + "px",
                    'backgroundColor': tags_bg_color,
                    'color': tags_txt_color,
                    'borderWidth': tags_border_width + "px",
                    'borderStyle': tags_border_style,
                    'borderColor': tags_border_color,
                    'borderRadius': tags_border_radius + "px",
                    'boxShadow' : tags_boxsh_type + " " + tags_boxsh_h + "px " + tags_boxsh_v + "px " + tags_boxsh_blur + "px " + tags_boxsh_spread + "px " +  tags_boxsh_color,
                    'textShadow' : tags_txtsh_h + "px " + tags_txtsh_v + "px " + tags_txtsh_blur + "px " + tags_txtsh_color,
                    'letterSpacing' : tags_txt_letter_spacing,
                    'wordSpacing' : tags_txt_word_spacing,
                    'lineHeight' : tags_txt_line_height + "%",
                    'fontSize' : tags_txt_size,
                    'direction' : tags_txt_direction,
                    'unicodeBidi' : tags_txt_unicode_bibi,
                    'textDecoration' : tags_txt_decoration,
                    'textTransform' : tags_txt_transform
                });
                });

        } else if (slider_init==3) {
            var container_bg_color = $('input#cg_bg_colorpicker_input').val(),
                view = $('select#album_view').val(),
                hover = $('select#hover_type').val(),
                thumbnail_size = parseInt($('div#cg_img_size_selector').slider("value")),
                container_bg_color = $('input#cg_bg_colorpicker_input').val(),
                images_border = parseInt($('input#cg_image_border_w_selector').spinner("value")),
                images_border_r = parseInt($('input#cg_image_border_r_selector').spinner("value")),
                images_border_color = $('input#image_border_colorpicker_input').val(),
                images_border_type = $('select#cg_image_border_t_selector').val(),
                ///////// 
                images_box_sh_h = parseInt($('input#cg_image_box_shadow_h_selector').spinner("value")),
                images_box_sh_v = parseInt($('input#cg_image_box_shadow_v_selector').spinner("value")),
                images_box_sh_blur = parseInt($('input#cg_image_box_shadow_b_selector').spinner("value")),
                images_box_sh_spread = parseInt($('input#cg_image_box_shadow_s_selector').spinner("value")),
                images_box_sh_type = $('select#cg_image_box_shadow_t_selector').val(),
                images_box_sh_color = $('input#image_box_shadow_colorpicker_input').val(),

                images_border_hover = parseInt($('input#cg_image_border_w_selector_hover').spinner("value")),
                images_border_r_hover = parseInt($('input#cg_image_border_r_selector_hover').spinner("value")),
                images_border_color_hover = $('input#image_border_colorpicker_input_hover').val(),
                images_border_type_hover = $('select#cg_image_border_t_selector_hover').val(),
                ///////// 
                images_box_sh_h_hover = parseInt($('input#cg_image_box_shadow_h_selector_hover').spinner("value")),
                images_box_sh_v_hover = parseInt($('input#cg_image_box_shadow_v_selector_hover').spinner("value")),
                images_box_sh_blur_hover = parseInt($('input#cg_image_box_shadow_b_selector_hover').spinner("value")),
                images_box_sh_spread_hover = parseInt($('input#cg_image_box_shadow_s_selector_hover').spinner("value")),
                images_box_sh_type_hover = $('select#cg_image_box_shadow_t_selector_hover').val(),
                images_box_sh_color_hover = $('input#image_box_shadow_colorpicker_input_hover').val(),

                top1 = $('input#cg_img_icon_top1_selector').val()*1,
                left1 = $('input#cg_img_icon_left1_selector').val()*1,
                top2 = $('input#cg_img_icon_top2_selector').val()*1,
                left2 = $('input#cg_img_icon_left2_selector').val()*1,


                anim_speed = $("div#cg_img_anim_speed").slider("value");

                $("input#cg_image_border_w_selector_hover").spinner("option", "disabled", true);
                $("select#cg_image_border_t_selector_hover").selectmenu("option", "disabled", true);

                $("div.preview_container_images").find("div.image_preview_container").css('backgroundColor', container_bg_color);

                var image_width = 0,
                    image_height = 0;

                ////console.log(view);
                ////console.log(thumbnail_size);
                switch (view) {
                    case '1':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break;
                    case '2':
                        /////////
                        image_width = thumbnail_size;
                        image_height = 'auto';
                        break;
                    case '3':
                        /////////
                        image_width = 'auto';
                        image_height = thumbnail_size;
                        break;
                    case '4':
                        /////////
                        image_width = 'auto';
                        image_height = thumbnail_size;
                        break;
                    case '5':
                        /////////
                        image_width = thumbnail_size;
                        image_height = 'auto';
                        break;
                    case '6':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break;
                    case '7':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break;
                    case '8':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break;
                    case '9':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break; 
                    case '10':
                        /////////
                        image_width = thumbnail_size;
                        image_height = thumbnail_size;
                        break;
                    default:
                        ///////////////
                }
                var radius = "%";
                if (image_width==image_height) {
                    $("div.preview_container_images").find("div.image_preview_container").find('div.image_wrapper').css({
                        width: image_width + "px",
                        height: image_height + "px",
                        overflow: "hidden"
                    }).find("img.main_image").css({
                        width: 'auto',
                        height: image_height + "px",
                        maxWidth: 'none'
                    });
                    var calc_margin = (parseInt($("div.preview_container_images").find("div.image_preview_container").find("img.main_image").width())-thumbnail_size)/2;
                    $("div.preview_container_images").find("div.image_preview_container").find("img.main_image").css("marginLeft", -calc_margin + "px");
                } else if (image_height == 'auto') {
                    $("div.preview_container_images").find("div.image_preview_container").find('div.image_wrapper').css({
                        width: image_width + "px",
                        height: image_width + image_width/2 + "px",
                        overflow: "hidden"
                    }).find("img.main_image").css({
                        width: 'auto',
                        height: image_width + image_width/2 + "px",
                        maxWidth: 'none'
                    });
                    radius = "px";
                    var calc_margin = (parseInt($("div.preview_container_images").find("div.image_preview_container").find("img.main_image").width())-thumbnail_size)/2;
                    $("div.preview_container_images").find("div.image_preview_container").find("img.main_image").css("marginLeft", -calc_margin + "px");
                } else if (image_width == 'auto') {
                    radius = "px";
                    $("div.preview_container_images").find("div.image_preview_container").find("img.main_image").css({
                        width: 'auto',
                        height: image_height + "px",
                        marginLeft: 0
                    });
                    $("div.preview_container_images").find("div.image_preview_container").find('div.image_wrapper').css({
                        width: $("div.preview_container_images").find("div.image_preview_container").find("img.main_image").width(),
                        height: image_height + "px"
                    });
                }
                

                function applayBorderEffects (mode, normalClass, hoverClass, containerClass, containerClass_prev) {

                    var icon_effect = $('select#cg_image_icon_effect_selector').val();
                        iconSize = $('input#cg_img_icon_width_selector').val()*1,
                        actualWidth = $("div.preview_container_images").find('div.main_wrapper').find('div.image_wrapper').width()*1 + 2*images_border,
                        actualHeight = $("div.preview_container_images").find('div.main_wrapper').find('div.image_wrapper').height()*1 + 2*images_border;
                    var start_top1 = 0,
                        start_left1 = 0,
                        start_top2 = 0,
                        start_left2 = 0,
                        startClass = "",
                        endClass = "";

                    if (icon_effect==1) {
                        startClass = "";
                        endClass = "";
                        start_top1 = (actualHeight - iconSize)/2 + top1;
                        start_top2 = (actualHeight - iconSize)/2 + top2;
                        start_left1 = -100;
                        start_left2 =  actualWidth + 100;
                    } else if (icon_effect==2) {
                        startClass = "";
                        endClass = "";
                        start_top1 = -100;
                        start_top2 = actualHeight + 100;
                        start_left1 = (actualWidth - iconSize)/2 + left1;
                        start_left2 = (actualWidth - iconSize)/2 + left2;
                    } else if (icon_effect==3) {
                        startClass = "";
                        endClass = "";
                        start_top1 = -100;
                        start_left1 = -100;
                        start_top2 = actualHeight + 100;
                        start_left2 = actualWidth + 100;
                    } else if (icon_effect==4) {
                        startClass = "rotated-y";
                        endClass = "non-rotated-y";
                        start_top1 = (actualHeight - iconSize)/2 + top1;
                        start_top2 = (actualHeight - iconSize)/2 + top2;
                        start_left1 = (actualWidth - iconSize)/2 + left1;
                        start_left2 = (actualWidth - iconSize)/2 + left2;
                    } else if (icon_effect==5) {
                        startClass = "rotated-x";
                        endClass = "non-rotated-x";
                        start_top1 = (actualHeight - iconSize)/2 + top1;
                        start_top2 = (actualHeight - iconSize)/2 + top2;
                        start_left1 = (actualWidth - iconSize)/2 + left1;
                        start_left2 = (actualWidth - iconSize)/2 + left2;
                    }
                    ////console.log(start_top1 + " " + start_left1 + " " + start_top2 + " " + start_left2);
                    ////console.log(actualHeight + " " + actualWidth + " " + iconSize);
                    $('div.preview_container_images').find('div.icon_link').removeClass().addClass('icon').addClass('icon_link');
                    $('div.preview_container_images').find('div.icon_zoom').removeClass().addClass('icon').addClass('icon_zoom');
                    $("div.preview_container_images").find('div.main_wrapper').css({
                        'transition' : 'all ' + anim_speed/1000 + "s",
                        'width' : $("div.preview_container_images").find('div.main_wrapper').find('div.image_wrapper').width()*1 + 2*images_border + "px",
                        'height' : $("div.preview_container_images").find('div.main_wrapper').find('div.image_wrapper').height()*1 + 2*images_border + "px",
                        'borderRadius' : images_border_r + radius,
                        'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color
                    }).find("div.image_wrapper").css({
                        'transition' : 'all ' + anim_speed/1000 + "s",
                        'borderWidth': images_border + "px",
                        'borderStyle': images_border_type,
                        'borderColor': images_border_color,
                        'borderRadius': images_border_r + radius,
                        '-webkit-mask-image' : 'url(components/com_creativegallery/assets/images/dummy.png)'
                    }).find('img.main_image').css({
                        'transition' : 'all ' + anim_speed/1000 + "s"
                    }).siblings('div.icon').css('transition' , 'all ' + anim_speed/1000 + "s")
                    .siblings('div.overlay').css('transition' , 'all ' + anim_speed/1000 + "s");
                    $("div.preview_container_images").find('div#image_normal')
                    .find('div.icon_zoom').css({
                            'top': start_top1 + "px",
                            'left': start_left1 + "px"
                    }).addClass(startClass).siblings('div.icon_link').css({
                            'top': start_top2 + "px",
                            'left': start_left2 + "px"
                    }).addClass(startClass);
                    $("div.preview_container_images").find('div#image_animated')
                    .find('div.icon_zoom').css({
                            'top': start_top1 + "px",
                            'left': start_left1 + "px"
                    }).removeClass(endClass).addClass(startClass).siblings('div.icon_link').css({
                            'top': start_top2 + "px",
                            'left': start_left2 + "px"
                    }).removeClass(endClass).addClass(startClass);
                    $("div.preview_container_images").find('div#image_hover')
                    .find('div.icon_zoom').css({
                            'top': (actualHeight - iconSize)/2 + top1 + "px",
                            'left': (actualWidth - iconSize)/2 + left1 + "px"
                    }).removeClass(startClass).addClass(endClass).siblings('div.icon_link').css({
                            'top': (actualHeight - iconSize)/2 + top2 + "px",
                            'left': (actualWidth - iconSize)/2 + left2 + "px"
                    }).removeClass(startClass).addClass(endClass);


                    if (mode) {
                        $("div.preview_container_images").find('div#image_hover').css({
                             'borderRadius': images_border_r_hover + radius,
                             'boxShadow' : images_box_sh_type_hover + " " + images_box_sh_h_hover + "px " + images_box_sh_v_hover + "px " + images_box_sh_blur_hover + "px " + images_box_sh_spread_hover + "px " +  images_box_sh_color_hover
                        }).find("div.image_wrapper").css({
                            'borderWidth': images_border + "px",
                            'borderStyle': images_border_type,
                            'borderColor': images_border_color_hover,
                            'borderRadius': images_border_r_hover + radius
                        }).siblings('div.icon_zoom').css({
                            'top': (actualHeight - iconSize)/2 + top1 + "px",
                            'left': (actualWidth - iconSize)/2 + left1 + "px"
                        }).removeClass(startClass).addClass(endClass).siblings('div.icon_link').css({
                            'top': (actualHeight - iconSize)/2 + top2 + "px",
                            'left': (actualWidth - iconSize)/2 + left2 + "px"
                        }).removeClass(startClass).addClass(endClass);
                        $("div.preview_container_images").find('div#image_normal').find("img.main_image").removeClass().addClass('main_image ' + normalClass);
                        $("div.preview_container_images").find('div#image_animated').find("img.main_image").removeClass().addClass('main_image ' + normalClass);
                        $("div.preview_container_images").find('div#image_hover').find("img.main_image").removeClass().addClass('main_image ' + hoverClass);
                        $("div.preview_container_images").find('div#image_animated').find("div.image_wrapper").removeClass().addClass('image_wrapper ' + containerClass);
                        $("div.preview_container_images").find('div#image_hover').find("div.image_wrapper").removeClass().addClass('image_wrapper ' + containerClass_prev);
                        directionAwareStop ();
                        $("div.preview_container_images").find('div#image_animated').hover(function() {
                            $(this).css({
                                'borderRadius': images_border_r_hover + radius,
                                'boxShadow' : images_box_sh_type_hover + " " + images_box_sh_h_hover + "px " + images_box_sh_v_hover + "px " + images_box_sh_blur_hover + "px " + images_box_sh_spread_hover + "px " +  images_box_sh_color_hover
                            }).find("div.image_wrapper").css({
                                'borderWidth': images_border + "px",
                                'borderStyle': images_border_type,
                                'borderColor': images_border_color_hover,
                                'borderRadius': images_border_r_hover + radius
                            }).find('img.main_image').removeClass().addClass('main_image ' + hoverClass)
                            .siblings('div.icon_zoom').css({
                                'top': (actualHeight - iconSize)/2 + top1 + "px",
                                'left': (actualWidth - iconSize)/2 + left1 + "px"
                            }).removeClass(startClass).addClass(endClass).siblings('div.icon_link').css({
                                'top': (actualHeight - iconSize)/2 + top2 + "px",
                                'left': (actualWidth - iconSize)/2 + left2 + "px"
                            }).removeClass(startClass).addClass(endClass);
                            


                            /* Stuff to do when the mouse enters the element */
                        }, function() {
                            $(this).css({
                                'borderRadius' : images_border_r + radius,
                                'boxShadow' : images_box_sh_type + " " + images_box_sh_h + "px " + images_box_sh_v + "px " + images_box_sh_blur + "px " + images_box_sh_spread + "px " +  images_box_sh_color
                            }).find("div.image_wrapper").css({
                                'borderWidth': images_border + "px",
                                'borderStyle': images_border_type,
                                'borderColor': images_border_color,
                                'borderRadius': images_border_r + radius,
                            }).find('img.main_image').removeClass().addClass('main_image ' + normalClass).siblings('div.icon_zoom').css({
                                'top': start_top1 + "px",
                                'left': start_left1 + "px"
                            }).removeClass(endClass).addClass(startClass).siblings('div.icon_link').css({
                                'top': start_top2 + "px",
                                'left': start_left2 + "px"
                            }).removeClass(endClass).addClass(startClass);
                            /* Stuff to do when the mouse leaves the element */
                        });
                    } else {
                        $("div.preview_container_images").find('div#image_animated').off("hover");
                    }
                }

                function renderIcons () {
                    var iconAppearance = $('select#cg_image_icon_type_selector').val(),
                        zoomIcon = $('select#cg_image_icon_zoom_template_selector').val(),
                        linkIcon = $('select#cg_image_icon_link_template_selector').val(),
                        iconBgColor = $('input#img_icon_colorpicker_input').val(),
                        iconSize = $('input#cg_img_icon_width_selector').val(),
                        iconProp = $('input#cg_img_icon_prop_selector').val(),
                        iconBorderW = $('input#cg_icons_border_w_selector').val(),
                        iconBorderR = $('input#cg_icons_border_r_selector').val(),
                        iconBorderT = $('select#cg_icons_border_t_selector').val(),
                        iconBorderColor = $('input#icons_border_colorpicker_input').val(),
                        iconBoxShadowH = $('input#cg_icons_box_shadow_h_selector').val(),
                        iconBoxShadowV = $('input#cg_icons_box_shadow_v_selector').val(),
                        iconBoxShadowBlur = $('input#cg_icons_box_shadow_b_selector').val(),
                        iconBoxShadowSpread = $('input#cg_icons_box_shadow_s_selector').val(),
                        iconBoxShadowT = $('select#cg_icons_box_shadow_t_selector').val(),
                        iconBoxShadowColor = $('input#icons_box_shadow_colorpicker_input').val();
                        ////console.log(iconBgColor);
                    $('div.preview_container_images').find("div.icon").css({
                        'backgroundColor' : iconBgColor,
                        'backgroundSize' : iconProp + '%',
                        'width' : iconSize + "px",
                        'height' : iconSize + "px",
                        'borderWidth': iconBorderW + "px",
                        'borderStyle': iconBorderT,
                        'borderColor': iconBorderColor,
                        'borderRadius': iconBorderR + '%',
                        'boxShadow' : iconBoxShadowT + " " + iconBoxShadowH + "px " + iconBoxShadowV + "px " + iconBoxShadowBlur + "px " + iconBoxShadowSpread + "px " +  iconBoxShadowColor
                    });
                    $('div.preview_container_images').find("div.icon_zoom").css({
                        'backgroundImage': "URL(components/com_creativegallery/assets/images/icons/image_icons/" + zoomIcon + ".png)"
                        // 'top' : top1 + "px",
                        // 'left' : left1 + "px"
                    });
                    $('div.preview_container_images').find("div.icon_link").css({
                        'backgroundImage': "URL(components/com_creativegallery/assets/images/icons/image_icons/" + linkIcon + ".png)"
                        // 'top' : top2 + "px",
                        // 'left' : left2 + "px"
                    });
                    switch (iconAppearance) {
                        case'both': 
                            $('div.preview_container_images').find("div.icon").css({
                                'display': 'block'
                            });
                            break;
                        case 'link_only':
                            $('div.preview_container_images').find("div.icon_link").css({
                                'display': 'block'
                            });
                            $('div.preview_container_images').find("div.icon_zoom").css({
                                'display': 'none'
                            });
                            break;
                        case 'zoom_only': 
                            $('div.preview_container_images').find("div.icon_link").css({
                                'display': 'none'
                            });
                            $('div.preview_container_images').find("div.icon_zoom").css({
                                'display': 'block'
                            });
                            break;
                        case 'none':
                            $('div.preview_container_images').find("div.icon").css({
                                'display': 'none'
                            });
                           break;
                        default:
                            break;
                    }


                }
                renderIcons ();
                ////console.log(hover);
                if (hover == 1 ) {
                    applayBorderEffects(true, "gray", "none", "none", "none");
                } else if (hover == 2 ) {
                    applayBorderEffects(true, "none", "gray", "none", "none");                    
                } else if (hover == 3 ) {
                    applayBorderEffects(true, "none", "blur", "none", "none");    
                } else if (hover == 4 ) {
                    applayBorderEffects(true, "none", "brightness", "none", "none");    
                } else if (hover == 5 ) {
                    applayBorderEffects(true, "none", "sepia", "none", "none");   
                } else if (hover == 6  ) {
                    applayBorderEffects(true, "none", "contrast", "none", "none");   
                } else if (hover == 7 ) {
                    applayBorderEffects(true, "none", "hue-rotate", "none", "none");   
                } else if (hover == 8 ) {
                    applayBorderEffects(true, "none", "brightness1", "none", "none");  
                } else if (hover == 9 ) {
                    applayBorderEffects(true, "none", "invert", "none", "none");  
                } else if (hover == 10 ) {
                    applayBorderEffects(true, "none", "saturate", "none", "none");  
                } else if (hover == 11 ) {
                    applayBorderEffects(true, "none", "none", "caption1", "caption1_hover");
                } else if (hover == 12 ) {
                    applayBorderEffects(true, "none", "none", "zoom", "zoom_hover");
                } else if (hover==13) { //Custom 
                    applayBorderEffects(true, "none", "none", "rotate", "rotate_hover");
                }  else if (hover==14) { //Custom 
                    applayBorderEffects(true, "none", "none", "direction_aware", "direction_aware");
                    directionAwareInit ();
                }

        } else {
            ////console.log("not init");
        }
    }
    $("button#remove_images_button").on("click", function () {
        $("div#confirm_dialog").dialog( "open");
    })

    $("table.table-main").on("keyup", "input.cg_image_name", function () {
        var val=$(this).val();
        $(this).parents("td.table-image-name").find("input.cg_image_title").val(val);
    })

    // table sortable function

    $("table.table-main").children("tbody").sortable({appendTo: "parent", 
        axis: "y", 
        containment: "parent", 
        tolerance : "pointer",
        "delay" : 150, 
        "revert" : 300, 
        "handle" : "td.table-reorder>img",
        cancel: "td.table-select, td.table-image-name, table-thumbnail , table-filename , table-title, table-image-tags, table-published", 
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
            });
            $helper.addClass("table-placeholder");
                return $helper;
        },
        update: function (evt, ui) {
            var arr = $("table.table-main").children("tbody").sortable("toArray");
            $("input#cg_album_ordering").val(arr.join(" "));
           // ////console.log(arr.join(" "));
        }
    });   

    $("table.table-main").find("th.table-select>input").on("click", function () {
        var checkBoxes  = $("table.table-main").find("td.table-select>input");
        if ($(this).prop("checked")) {
            checkBoxes.prop("checked", 1);
        }else {
            checkBoxes.prop("checked", 0);
        };
        
        
    })

    $("table.table-main").on("click", "img.cg_publish_icon", function () {
        if ($(this).attr("title") == "Click to Publish") {
            $(this).attr("src", "components/com_creativegallery/assets/images/icons/published.png");
            $(this).attr("title", "Published and Current");
            $(this).siblings("input").val(1);
        } else {
            $(this).attr("src", "components/com_creativegallery/assets/images/icons/not_published.png");
            $(this).attr("title", "Click to Publish");
            $(this).siblings("input").val(0);
        }
    })


    $("div#filemanager_dialog").dialog({ autoOpen : false });
    $("div#confirm_dialog").dialog( {
            height  : 200, 
            width   : 400, 
            modal   : true, 
            autoOpen    : false,
            title   : "Confirm Delete",
            show    : { effect : "slideDown", duration : 400 }, 
            hide    : { effect: "slideUp", duration: 400 },
            buttons : [ {
                text: "Ok",
                    click: function() {
                        $("table.table-main>tbody").find("input:checked").parents("tr").remove();
                        var checkBoxes  = $("table.table-main").find("th.table-select>input");
                        checkBoxes.prop("checked", 0);
                        indexCorrection ();
                        $( this ).dialog( "close" );
                    }
                }, {
                    text: "Cancel",
                    click: function() {
                        $( this ).dialog( "close" );
                }
            }] 
            
            });
    $("div#tags_info_dialog").dialog( {
            height  : 200, 
            width   : 400, 
            modal   : true, 
            autoOpen    : false,
            title   : "Info",
            show    : { effect : "slideDown", duration : 400 }, 
            hide    : { effect: "slideUp", duration: 400 },
            buttons : [ {
                text: "Ok",
                    click: function() {
                        $(this).dialog("close");
                    }
                }
            ]
        });


    $("button#add_preview").on("click", function () {
        filemanagerOpen ("Add Preview Image");
	});
	$("button#add_images_button").on("click", function () {
		filemanagerOpen ("Add Images");
	});
	


    $("button#cg_upload").on("click", function() {
		$("div#filemanager_wrapper").animate({"opacity" : 0}, 300, "linear" , function () {
			$("div#filemanager_wrapper").hide();
            deInitFileManager($);
			$("div#fileupload_wrapper").show();
			$("div#fileupload_wrapper").animate({opacity : 1}, 300);
		})
		$("table#upload_present").html("");
		$('#progress .bar').css('width', '0%');
		$("#progress>span").text(0 + "%");
		$("input#fileupload").fileupload({
    		dataType: 'json',
            url: "./index.php?option=com_creativegallery&view=creativeuploader&layout=index&format=json" + "&upload_dir=" + $('ul#directory_tree').find('li').last().attr('data-path').split('/').join('-_-'),
    		add: function (e, data) {
    			$('span#upload_start').click(function () {
               		data.submit();
                });
    			$('span#upload_cancel').click(function () {
                    data.abort();
                });
    		},
        	drop: function (e, data) {
        		$("table#upload_present").html("");
        		$('#progress .bar').css('width', '0%');
        		$("#progress>span").text(0 + "%");
    			$.each(data.files, function (index, file) {
                    var new_row = document.createElement("TR"),
                	cell1 = document.createElement("TD"),
                	cell2 = document.createElement("TD"),
                	cell3 = document.createElement("TD"),
                	span = document.createElement("SPAN"),
                	div = document.createElement("DIV");
                	span.innerHTML = "0%";
                	div.className = "bar";
                	div.setAttribute("data-status", "pending");
                	$('<div/>').addClass("uploader_progress").append(span).append(div).appendTo(cell3);
					$('<p/>').text(file.name).appendTo(cell1);
                	$('<p/>').text(bytesToSize(file.size)).appendTo(cell2);
	                new_row.appendChild(cell1);
    	            new_row.appendChild(cell2);
					new_row.appendChild(cell3);
        	        $("table#upload_present").append(new_row);
            	})
        	},
        	change: function (e, data) {
        		$("table#upload_present").html("");
        		$('#progress .bar').css('width', '0%');
        		$("#progress>span").text(0 + "%");
    			$.each(data.files, function (index, file) {
                	var new_row = document.createElement("TR"),
                		cell1 = document.createElement("TD"),
                		cell2 = document.createElement("TD"),
                		cell3 = document.createElement("TD"),
                		span = document.createElement("SPAN"),
                		div = document.createElement("DIV");
                		span.innerHTML = "0%";
                		div.className = "bar";
                		div.setAttribute("data-status", "pending");
                		$('<div/>').addClass("uploader_progress").append(span).append(div).appendTo(cell3);
						$('<p/>').text(file.name).appendTo(cell1);
                		$('<p/>').text(bytesToSize(file.size)).appendTo(cell2);
	                	new_row.appendChild(cell1);
    	            	new_row.appendChild(cell2);
						new_row.appendChild(cell3);
        	        	$("table#upload_present").append(new_row);
            	})
        	},
        	done: function (e, data) {
        		$('span#upload_start').off("click");
        		$('span#upload_cancel').off("click");
        		var target = $("div#fileupload_wrapper");
            },
            stop: function (e) {
            	$('span#upload_start').off("click");
        		$('span#upload_cancel').off("click");
        		$('#progress .bar').removeClass("anim");
			},
    		progressall: function (e, data) {
        		var progress = parseInt(data.loaded / data.total * 100, 10);
        		$('#progress .bar').addClass("anim").css(
            		'width',
            		progress + '%'
        		);
        		$("#progress>span").text(progress + "%");
        		if(progress == 100) {
    				$('#progress .bar').removeClass("anim");
    			}
        	},
    		progress: function (e, data) {
    			var progress = parseInt(data.loaded / data.total * 100, 10);
    			$("div[data-status='pending']").first().siblings("span").text(progress + "%");
    			$("div[data-status='pending']").first().addClass("anim").css (
    					'width',
            			progress + '%'
    				);
        		if(progress == 100) {
    				$("div[data-status='pending']").removeClass("anim").first().attr("data-status", "done");
    			}
    		},
    		replaceFileInput: true,
    		autoUpload: false,
    		dropZone: $('div.dragarea')
		});
	});
	$("span#upload_back").on("click", function() {
		$("div#fileupload_wrapper").animate({"opacity" : 0}, 300, "linear" , function () {
			$("div#fileupload_wrapper").hide();
			$("div#filemanager_wrapper").show();
			$("div#filemanager_wrapper").animate({opacity : 1}, 300);
			initFileManager($, base_path);
		})
	});
    $("button#cg_weblink").on('click', function(event) {
        event.preventDefault();
        $("div#filemanager_wrapper").animate({opacity: 0}, 300, 'linear', function() {
            $("div#filemanager_wrapper").hide();
            deInitFileManager($);
            $("div#weblink_wrapper").show();
            $("div#weblink_wrapper").animate({opacity : 1}, 300);
            initUrlAdder($);
        });
    });
    $("span#weblink_beck").on("click", function() {
        $("div#weblink_wrapper").animate({"opacity" : 0}, 300, "linear" , function () {
            $("div#weblink_wrapper").hide();
            $("div#filemanager_wrapper").show();
            $("div#filemanager_wrapper").animate({opacity : 1}, 300);
            initFileManager($, base_path);
        })
    })

    


    $("div#add_tags_dialog").dialog( {
            "height" : 300, 
            "width" : 600, 
            modal: true, 
            autoOpen : false,
            show : {
                "effect" : "slideDown",
                "duration" : 400
            }, 
            hide: { 
                effect: "slideUp", 
                duration: 400 
            },
            buttons : [ {
                text: "Ok",
                    click: function() {
                        if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                            var mainAlbumTagsContainer = $("div#cg_album_tags"),
                                tagsList = Array(),
                                selectedTagsList = Array(),
                                selectedTagsListUnfiltered = $("div#add_tags_part").find("div.tagmanager_line").toArray();
                            for (var i = 0; i < selectedTagsListUnfiltered.length; i++) {
                                var currentVal = $(selectedTagsListUnfiltered[i]).find("select").val(),
                                    pos = i;
                                    for (var j = i; j < selectedTagsListUnfiltered.length; j++) {
                                        if ($(selectedTagsListUnfiltered[j]).find("select").val()==currentVal) {
                                            pos = j;
                                        };
                                    };
                                    if (pos == i) {
                                        selectedTagsList.push(selectedTagsListUnfiltered[i]);
                                    };
                            };
                                mainAlbumTagsContainer.find("div.tag").remove();
                            for (var i = 0; i < selectedTagsList.length; i++) {
                                var currentTagId = $(selectedTagsList[i]).find("select").val();
                                if (currentTagId=="-1") { continue;};

                                var currentTagName = $(selectedTagsList[i]).find(":selected").text(),
                                    newTag = mainAlbumTagsContainer.find("div#album_tag_template").clone().removeAttr('id').addClass('tag').show();
                                tagsList.push(currentTagId);
                                newTag.find("span").text(currentTagName).attr("data-id", currentTagId);
                                mainAlbumTagsContainer.append(newTag);
                            };
                            mainAlbumTagsContainer.find("input").val(tagsList.join(" "));
                        } else {
                            var selectedItems = $('table.table-main').children('tbody').find(":checked").toArray(),
                                selectedItemsCount = selectedItems.length;
                            for (var k = 0; k < selectedItems.length; k++) {
                                

                                var mainImageTagsContainer = $(selectedItems[k]).parent("td").siblings('td.table-image-tags');
                                    tagsList = Array(),
                                    selectedTagsList = Array(),
                                    selectedTagsListUnfiltered = $("div#add_tags_part").find("div.tagmanager_line").toArray();
                                if (selectedItemsCount==1) {
                                    mainImageTagsContainer.find("div.tag").remove(); 
                                    mainImageTagsContainer.find("input").val("");
                                    ////console.log("bla");
                                }
                                for (var i = 0; i < selectedTagsListUnfiltered.length; i++) {
                                    var currentVal = $(selectedTagsListUnfiltered[i]).find("select").val(),
                                    pos = i;
                                    for (var j = i; j < selectedTagsListUnfiltered.length; j++) {
                                        if ($(selectedTagsListUnfiltered[j]).find("select").val()==currentVal) {
                                            pos = j;
                                        };
                                    };
                                    if (pos == i) {
                                        selectedTagsList.push(selectedTagsListUnfiltered[i]);
                                    };
                                };
                                var existingTagsList = mainImageTagsContainer.find("div.tag").toArray(),
                                    existingTagsIdList = Array();
                                for (var i = 0; i < existingTagsList.length; i++) {
                                    var id = $(existingTagsList[i]).find("span").attr("data-id");
                                    existingTagsIdList.push(id);
                                    tagsList.push(id);
                                };
                                for (var i = 0; i < selectedTagsList.length; i++) {
                                    var currentTagId = $(selectedTagsList[i]).find("select").val();
                                    if (currentTagId=="-1") { continue;};
                                    if (selectedItemsCount!=1) {
                                        if ($.inArray(currentTagId, existingTagsIdList)!="-1") { continue;};
                                    };
                                    var currentTagName = $(selectedTagsList[i]).find(":selected").text(),
                                    newTag = $("div#album_tag_template").clone().removeAttr('id').addClass('tag').show();
                                    tagsList.push(currentTagId);
                                    newTag.find("span").text(currentTagName).attr("data-id", currentTagId);
                                    //////console.log(newTag);
                                    mainImageTagsContainer.append(newTag);
                                };
                            mainImageTagsContainer.find("input").val(tagsList.join(" "));
                            };
                        }
                            $(this).dialog( "close" );
                        }
                }, {
                    text: "Cancel",
                    click: function() {
                        $( this ).dialog( "close" );
                }
            }],
            close: function () {
                deInitTagManager($);
            },
            open: function () {
                 initTagManager($);
            }
            
            });


    $("button#add_tags_button").on('click', function(event) {
        event.preventDefault();
        $("div#add_tags_dialog").dialog("option", "title", "Add Album Tags");
        $("div#add_tags_dialog").dialog("open");
       
    });

    $("button#add_image_tags_button").on('click', function(event) {
        event.preventDefault();
        if ($('table.table-main').children('tbody').find(":checked").length==0) {
            $("div#tags_info_dialog").dialog("open");
        } else {
            $("div#add_tags_dialog").dialog("option", "title", "Add Image Tags");
            $("div#add_tags_dialog").dialog("open");
        };
    });

    $("body").on('click', 'img.delete_icon', function(event) {
        //////console.log("click");
        event.preventDefault();
        var input = $(this).parent('div.tag').siblings('input'),
            valueList = input.val().split(" "),
            currentVal = $(this).siblings('span').attr('data-id');
            newvalueList = Array();
            for (var i = 0; i < valueList.length; i++) {
                if (valueList[i]===currentVal) {
                    continue;
                };
                newvalueList.push(valueList[i]);
            };
            input.val(newvalueList.join(" "));
            //////console.log(newvalueList);
            $(this).parent('div.tag').remove();
    });


    $(window).resize(function(event) {
        viewPreview();
    });

    $(window).scroll(function(event) {
        var scrollTop = parseInt($(window).scrollTop()),
            pageCurrentHeight = parseInt($('div.options_wrapper1').height()),
            previewHeight = parseInt($('div.preview_container').height());
        if (scrollTop + previewHeight <= pageCurrentHeight) {
            $('div.preview_container').stop(true, false).animate({'margin-top': scrollTop + 10 + "px"} , 300);
        }
    });


    $('select#album_view').on('change', function(event) {
        event.preventDefault();
        var view = $(this).val(),
            size = $('div#cg_img_size_selector').slider("value");
            // if (view==6) {
            //     $('div#cg_img_size_selector').slider("value", 500);
            // }
            viewPreview ();
    });


    $('div#cg_max_image_per_page_selector').slider({
        animate: "fast",
        max: 100,
        min: 4,
        range: 'min',
        value: 20,
        create: function( event, ui ) {
            var value = $('div#cg_max_image_per_page_selector').siblings('input').val();
            $('div#cg_max_image_per_page_selector').slider("value", value);
            $('div#cg_max_image_per_page_selector').siblings('div.cg_max_image_per_page_selector_value').children('span').text($('div#cg_max_image_per_page_selector').slider("value"));
        },
        slide: function( event, ui ) {
            $('div#cg_max_image_per_page_selector').siblings('div.cg_max_image_per_page_selector_value').children('span').text($('div#cg_max_image_per_page_selector').slider("value"));
            viewPreview ();
        },
        change: function( event, ui ) {
            var size = $('div#cg_max_image_per_page_selector').slider("value"),
                view = $('select#album_view').val();
            $('div#cg_max_image_per_page_selector').siblings('div.cg_max_image_per_page_selector_value').children('span').text($('div#cg_max_image_per_page_selector').slider("value"));
            $('div#cg_max_image_per_page_selector').siblings('input').val($('div#cg_max_image_per_page_selector').slider("value"));
            viewPreview ();
            //ajaxCreate(album_id, view, size);
        }
    });


    $('div#cg_img_size_selector').slider({
        animate: "fast",
        max: 500,
        min: 50,
        range: 'min',
        value: 100,
        create: function( event, ui ) { 
            var value = $('div#cg_img_size_selector').siblings('input').val();
            $('div#cg_img_size_selector').slider("value", value);
            $('div#cg_img_size_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_size_selector').slider("value") + "px");
        },
        slide: function( event, ui ) { 
            $('div#cg_img_size_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_size_selector').slider("value") + "px"); 
            viewPreview ();
        },
        change: function( event, ui ) { 
            var size = $('div#cg_img_size_selector').slider("value"),
                view = $('select#album_view').val();
            $('div#cg_img_size_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_size_selector').slider("value") + "px");
            $('div#cg_img_size_selector').siblings('input').val($('div#cg_img_size_selector').slider("value"));  
            viewPreview ();            
            //ajaxCreate(album_id, view, size);
        }
    });
    $('div#cg_img_count_selector').slider({
        animate: "fast",
        max: 20,
        min: 3,
        range: 'min',
        value: 3,
        create: function( event, ui ) { 
            var value = $('div#cg_img_count_selector').siblings('input').val();
            $('div#cg_img_count_selector').slider("value", value);
            $('div#cg_img_count_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_count_selector').slider("value")); 
                 
            // ////console.log("start");
        },
        slide: function( event, ui ) { 
            $('div#cg_img_count_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_count_selector').slider("value")); 
            viewPreview ();
        },
        change: function( event, ui ) { 
            $('div#cg_img_count_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_count_selector').slider("value"));
            $('div#cg_img_count_selector').siblings('input').val($('div#cg_img_count_selector').slider("value"));  
             viewPreview ();   
        }
    });
    $('div#cg_img_margin_selector').slider({
        animate: "fast",
        max: 50,
        min: 0,
        range: 'min',
        value: 20,
        create: function( event, ui ) { 
            var value = $('div#cg_img_margin_selector').siblings('input').val();
            $('div#cg_img_margin_selector').slider("value", value);
            $('div#cg_img_margin_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_margin_selector').slider("value") + "px"); 
        },
        slide: function( event, ui ) { 
            $('div#cg_img_margin_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_margin_selector').slider("value") + "px"); 
            viewPreview ();            
        },
        change: function( event, ui ) { 
            $('div#cg_img_margin_selector').siblings('div.cg_img_size_selector_value').children('span').text($('div#cg_img_margin_selector').slider("value") + "px");
            $('div#cg_img_margin_selector').siblings('input').val($('div#cg_img_margin_selector').slider("value"));  
            viewPreview ();            
        }
    });
    


    $('div#cg_preview_speed').slider({
        animate: "fast",
        max: 20,
        min: 1,
        range: 'min',
        value: 1,
        create: function( event, ui ) { 
            var value = $(this).siblings('input').val();
            $(this).slider("value", value);
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "s"); 
            //slider_init = true;         
            // ////console.log("start");
        },
        slide: function( event, ui ) { 
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "s"); 

        },
        change: function( event, ui ) { 
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "s");
            $(this).siblings('input').val($(this).slider("value"));  
        }
    });
    $('div#cg_preview_speed').siblings('input').attr('data-div-id', 'cg_preview_speed');
    $('div#cg_preview_height').slider({
        animate: "fast",
        max: 1000,
        min: 200,
        range: 'min',
        value: 1,
        create: function( event, ui ) { 
            var value = $(this).siblings('input').val();
            $(this).slider("value", value);
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "px"); 
        },
        slide: function( event, ui ) { 
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "px"); 
            viewPreview ();  
        },
        change: function( event, ui ) { 
            $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "px");
            $(this).siblings('input').val($(this).slider("value"));  
            viewPreview ();  
        }
    });
    $('div#cg_preview_height').siblings('input').attr('data-div-id', 'cg_preview_height');

    ///////////////////////// container

    //main

    function activateContOptions () {
        $("input#cg_cont_p_selector").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        var opacitySelector = $("input#cg_cont_o_selector"),
            colorpicker_div = $("div#cg_bg_colorpicker"),
            colorpicker_input = $('input#cg_bg_colorpicker_input');
        opacitySelector.attr('data-output', 'cg_bg_colorpicker_input');
        colorpicker_div.attr('data-output', 'cg_bg_colorpicker_input');
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });

    }
    activateContOptions ();
    
    function activateTagsOptions () {
        $("input#tags_m_selector").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        $("input#tags_p_h_selector").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        $("input#tags_p_v_selector").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        var opacitySelector = $("input#tags_bg_o_selector"),
            colorpicker_div = $("div#tags_colorpicker"),
            colorpicker_input = $('input#cg_tags_bg_colorpicker_input');

        opacitySelector.attr('data-output', 'cg_tags_bg_colorpicker_input');
        colorpicker_div.attr('data-output', 'cg_tags_bg_colorpicker_input');
        
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });
        $('select#tags_emabled').selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
    }
    activateTagsOptions ();

    function activateTagsHoverOptions () {
        $("div#cg_tags_anim_speed").slider({
            animate: "fast",
            max: 2000,
            min: 100,
            range: 'min',
            value: 1,
            create: function( event, ui ) { 
                var value = $(this).siblings('input').val();
                $(this).slider("value", value);
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms"); 
            },
            slide: function( event, ui ) { 
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms"); 
                viewPreview ();  
            },
            change: function( event, ui ) { 
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms");
                $(this).siblings('input').val($(this).slider("value"));  
                viewPreview ();  
            }
        });
        $('div#cg_tags_anim_speed').siblings('input').attr('data-div-id', 'cg_tags_anim_speed');
        $("input#tags_m_selector_hover").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        $("input#tags_p_h_selector_hover").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        $("input#tags_p_v_selector_hover").spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        var opacitySelector = $("input#tags_bg_o_selector_hover"),
            colorpicker_div = $("div#tags_colorpicker_hover"),
            colorpicker_input = $('input#cg_tags_bg_colorpicker_input_hover');

        opacitySelector.attr('data-output', 'cg_tags_bg_colorpicker_input_hover');
        colorpicker_div.attr('data-output', 'cg_tags_bg_colorpicker_input_hover');
        
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });
        
    }

    activateTagsHoverOptions ();

    // border
    function activateBorderSelector (options) {
        ////console.log(options);
        var opacitySelector = $(options.opacitySelector),
            colorpicker_div = $(options.colorpicker_div),
            colorpicker_input = $(options.colorpicker_input),
            widthSelector = $(options.widthSelector),
            radiusSelector = $(options.radiusSelector),
            typeSelector = $(options.typeSelector);

        opacitySelector.attr('data-output', colorpicker_input.attr('id'));
        colorpicker_div.attr('data-output', colorpicker_input.attr('id'));

        widthSelector.spinner({
            "max" : 20,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        radiusSelector.spinner({
            "max" : 50,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        typeSelector.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });
    }
    
    activateBorderSelector ({
        'opacitySelector' : "input#cg_cont_border_o_selector",
        'colorpicker_div' : "div#cont_border_colorpicker",
        'colorpicker_input' : 'input#cont_border_colorpicker_input',
        'widthSelector' : "input#cg_cont_border_w_selector",
        'radiusSelector' : "input#cg_cont_border_r_selector",
        'typeSelector' : "select#cg_cont_border_t_selector"
    });

    activateBorderSelector ({
        'opacitySelector' : "input#cg_tags_border_o_selector",
        'colorpicker_div' : "div#tags_border_colorpicker",
        'colorpicker_input' : 'input#tags_border_colorpicker_input',
        'widthSelector' : "input#cg_tags_border_w_selector",
        'radiusSelector' : "input#cg_tags_border_r_selector",
        'typeSelector' : "select#cg_tags_border_t_selector"
    })

    activateBorderSelector ({
        'opacitySelector' : "input#cg_tags_border_o_selector_hover",
        'colorpicker_div' : "div#tags_border_colorpicker_hover",
        'colorpicker_input' : 'input#tags_border_colorpicker_input_hover',
        'widthSelector' : "input#cg_tags_border_w_selector_hover",
        'radiusSelector' : "input#cg_tags_border_r_selector_hover",
        'typeSelector' : "select#cg_tags_border_t_selector_hover"
    })



    function activateShadowSelector (options) {
        var opacitySelector = $(options.opacitySelector),
            colorpicker_div = $(options.colorpicker_div),
            colorpicker_input = $(options.colorpicker_input),
            shadow_h_selector = $(options.shadow_h_selector),
            shadow_v_selector = $(options.shadow_v_selector),
            shadow_blur_selector = $(options.shadow_blur_selector),
            shadow_spread_selector = $(options.shadow_spread_selector),
            shadow_type_selector = $(options.shadow_type_selector);

        opacitySelector.attr('data-output', colorpicker_input.attr('id'));
        colorpicker_div.attr('data-output', colorpicker_input.attr('id'));

        // box shadow
        shadow_h_selector.spinner({
            "max" : 50,
            "min" : -50,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_v_selector.spinner({
            "max" : 50,
            "min" : -50,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_blur_selector.spinner({
            "max" : 30,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_spread_selector.spinner({
            "max" : 50,
            "min" : -50,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_type_selector.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });
    }

    function activateTextShadowSelector (options) {
        var opacitySelector = $(options.opacitySelector),
            colorpicker_div = $(options.colorpicker_div),
            colorpicker_input = $(options.colorpicker_input),
            shadow_h_selector = $(options.shadow_h_selector),
            shadow_v_selector = $(options.shadow_v_selector),
            shadow_blur_selector = $(options.shadow_blur_selector);

        opacitySelector.attr('data-output', colorpicker_input.attr('id'));
        colorpicker_div.attr('data-output', colorpicker_input.attr('id'));

        // box shadow
        shadow_h_selector.spinner({
            "max" : 50,
            "min" : -50,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_v_selector.spinner({
            "max" : 50,
            "min" : -50,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        shadow_blur_selector.spinner({
            "max" : 30,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },

            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });

    }


    function activateTextOptionsSelector (options) {
        var opacitySelector = $(options.opacitySelector),
            colorpicker_div = $(options.colorpicker_div),
            colorpicker_input = $(options.colorpicker_input),
            text_letter_spacing = $(options.text_letter_spacing),
            text_word_spacing = $(options.text_word_spacing),
            text_line_height = $(options.text_line_height),
            text_size = $(options.text_size),
            text_direction = $(options.text_direction),
            text_unicode_bibi = $(options.text_unicode_bibi),
            text_decoration = $(options.text_decoration),
            text_transform = $(options.text_transform);
        // box shadow
        opacitySelector.attr('data-output', colorpicker_input.attr('id'));
        colorpicker_div.attr('data-output', colorpicker_input.attr('id'));

        text_letter_spacing.spinner({
            "max" : 50,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        text_word_spacing.spinner({
            "max" : 50,
            "min" : 0,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        text_line_height.spinner({
            "max" : 300,
            "min" : 100,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        text_size.spinner({
            "max" : 70,
            "min" : 5,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        
        text_direction.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        text_unicode_bibi.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        text_decoration.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        text_transform.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
                
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });
    }

    function activateIconsOptionsSelector (options) {
        var opacitySelector = $(options.opacitySelector),
            colorpicker_div = $(options.colorpicker_div),
            colorpicker_input = $(options.colorpicker_input),
            width_selector = $(options.width_selector),
            proportion_selector = $(options.proportion_selector),
            appearance = $(options.appearance),
            link_template = $(options.link_template),
            effect = $(options.effect),
            top1 = $(options.top1),
            left1 = $(options.left1),
            top2 = $(options.top2),
            left2 = $(options.left2),
            zoom_template = $(options.zoom_template);

        opacitySelector.attr('data-output', colorpicker_input.attr('id'));
        colorpicker_div.attr('data-output', colorpicker_input.attr('id'));

        // box shadow
        width_selector.spinner({
            "max" : 128,
            "min" : 16,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        proportion_selector.spinner({
            "max" : 80,
            "min" : 5,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        top1.spinner({
            "max" : 250,
            "min" : -250,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        left1.spinner({
            "max" : 250,
            "min" : -250,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        top2.spinner({
            "max" : 250,
            "min" : -250,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        left2.spinner({
            "max" : 250,
            "min" : -250,
            "page": 5,
            "step" : 1,
            create: function( event, ui ) {
                var value = $(this).val();
                $(this).spinner('value', value);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).val(value);
                
                viewPreview ();
            },
            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }

                viewPreview ();
            }
        });
        

        appearance.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        link_template.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        zoom_template.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        effect.selectmenu({
            change: function( event, ui ) {
                viewPreview ();
            }
        });
        opacitySelector.spinner({
            "max" : 100,
            "min" : 0,
            "page": 10,
            "step" : 5,
            create: function( event, ui ) {
                var val = colorpicker_input.val(),
                    val_conv = val.replace("rgba(","");
                    val_conv = val_conv.replace(")","");
                var val_conv_arr = val_conv.split(","),
                    rgb_r = parseInt(val_conv_arr[0]),
                    rgb_g = parseInt(val_conv_arr[1]),
                    rgb_b = parseInt(val_conv_arr[2]),
                    op = parseFloat(val_conv_arr[3]);
                   //////console.log(val_conv_arr);
                colorpicker_input.attr({
                    'data-rgb-r': rgb_r,
                    'data-rgb-g': rgb_g,
                    'data-rgb-b': rgb_b,
                    'data-op': op
                });
                colorpicker_div.css('backgroundColor', val);
                //////console.log(rgb_r + " " + rgb_b + " " + rgb_g + " " + op);
                $(this).spinner('value', op*100);
            },
            stop: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },
            spin: function( event, ui ) {
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                
                $(this).val(value);
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            },

            change: function( event, ui ) {
                var value = $(this).spinner('value');
                $(this).spinner('value', value);
                if (value>$(this).spinner("option", "max")) {
                    $(this).val($(this).spinner("option", "max"));
                } else if (value<$(this).spinner("option", "min")) {
                    $(this).val($(this).spinner("option", "min"));
                } else {
                    $(this).val(value);
                }
                var value = $(this).spinner('value'),
                    rgb_r = colorpicker_input.attr('data-rgb-r'),
                    rgb_g = colorpicker_input.attr('data-rgb-g'),
                    rgb_b = colorpicker_input.attr('data-rgb-b'),
                    color = "rgba(" + rgb_r + "," + rgb_g + "," + rgb_b + "," + value/100 + ")";
                colorpicker_div.css('backgroundColor', color);
                colorpicker_input.val(color);
                viewPreview ();
            }
        });
        colorpicker_div.ColorPicker({
            //eventName: 'click',
            color: '#000000',
            flat: false,
            livePreview: true,
            onBeforeShow: function (argument) {
                var rgb = Object();
                rgb.r = colorpicker_input.attr('data-rgb-r'),
                rgb.g = colorpicker_input.attr('data-rgb-g'),
                rgb.b = colorpicker_input.attr('data-rgb-b'),
                op = opacitySelector.spinner("value")/100,
                val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                $(this).ColorPickerSetColor(rgb);
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
            },
            onChange: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var op = opacitySelector.spinner("value")/100,
                    val = "rgba(" + rgb.r + "," + rgb.g + "," + rgb.b + "," + op + ")";
                colorpicker_input.attr({
                    'data-rgb-r': rgb.r,
                    'data-rgb-g': rgb.g,
                    'data-rgb-b': rgb.b
                });
                colorpicker_div.css('backgroundColor', val);
                colorpicker_input.val(val);
                viewPreview ();   
            }
        });

    }

    activateIconsOptionsSelector ({
        'opacitySelector' : "input#cg_image_icon_o_selector",
        'colorpicker_div' : "div#cg_img_icon_colorpicker",
        'colorpicker_input' : 'input#img_icon_colorpicker_input',
        'width_selector' : "input#cg_img_icon_width_selector",
        'proportion_selector' : "input#cg_img_icon_prop_selector",
        'appearance' : "select#cg_image_icon_type_selector",
        'link_template' : "select#cg_image_icon_link_template_selector",
        'zoom_template' : "select#cg_image_icon_zoom_template_selector",
        'top1' : "input#cg_img_icon_top1_selector",
        'left1' : "input#cg_img_icon_left1_selector",
        'top2' : "input#cg_img_icon_top2_selector",
        'left2' : "input#cg_img_icon_left2_selector",
        'effect' : "select#cg_image_icon_effect_selector"
    })

    activateTextOptionsSelector ({
        'opacitySelector': "input#cg_text_o_selector",
        'colorpicker_div': "div#text_colorpicker",
        'colorpicker_input': "input#text_colorpicker_input",
        'text_letter_spacing': "input#cg_text_letter_spacing",
        'text_word_spacing': "input#cg_text_word_spacing",
        'text_line_height': "input#cg_text_line_height",
        'text_size': "input#cg_text_size",
        'text_direction': "select#cg_text_direction",
        'text_unicode_bibi': "select#cg_text_unicode_bibi",
        'text_decoration': "select#cg_text_decoration",
        'text_transform': "select#cg_text_transform"
    })

    activateTextOptionsSelector ({
        'opacitySelector': "input#cg_tags_text_o_selector",
        'colorpicker_div': "div#tags_text_colorpicker",
        'colorpicker_input': "input#tags_text_colorpicker_input",
        'text_letter_spacing': "input#cg_tags_text_letter_spacing",
        'text_word_spacing': "input#cg_tags_text_word_spacing",
        'text_line_height': "input#cg_tags_text_line_height",
        'text_size': "input#cg_tags_text_size",
        'text_direction': "select#cg_tags_text_direction",
        'text_unicode_bibi': "select#cg_tags_text_unicode_bibi",
        'text_decoration': "select#cg_tags_text_decoration",
        'text_transform': "select#cg_tags_text_transform"
    })


    activateShadowSelector ({
        'opacitySelector' : "input#cg_cont_box_shadow_o_selector",
        'colorpicker_div' : "div#cont_box_shadow_colorpicker",
        'colorpicker_input' : 'input#cont_box_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_cont_box_shadow_h_selector",
        'shadow_v_selector' : "input#cg_cont_box_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_cont_box_shadow_b_selector",
        'shadow_spread_selector' : "input#cg_cont_box_shadow_s_selector",
        'shadow_type_selector' : "select#cg_cont_box_shadow_t_selector"
    })


    
    /////// image

    //border
    activateBorderSelector ({
        'opacitySelector' : "input#cg_image_border_o_selector",
        'colorpicker_div' : "div#image_border_colorpicker",
        'colorpicker_input' : 'input#image_border_colorpicker_input',
        'widthSelector' : "input#cg_image_border_w_selector",
        'radiusSelector' : "input#cg_image_border_r_selector",
        'typeSelector' : "select#cg_image_border_t_selector"
    })
    
    // shadow
    activateShadowSelector ({
        'opacitySelector' : "input#cg_image_box_shadow_o_selector",
        'colorpicker_div' : "div#image_box_shadow_colorpicker",
        'colorpicker_input' : 'input#image_box_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_image_box_shadow_h_selector",
        'shadow_v_selector' : "input#cg_image_box_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_image_box_shadow_b_selector",
        'shadow_spread_selector' : "input#cg_image_box_shadow_s_selector",
        'shadow_type_selector' : "select#cg_image_box_shadow_t_selector"
    })
    
    activateShadowSelector ({
        'opacitySelector' : "input#cg_tags_box_shadow_o_selector",
        'colorpicker_div' : "div#tags_box_shadow_colorpicker",
        'colorpicker_input' : 'input#tags_box_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_tags_box_shadow_h_selector",
        'shadow_v_selector' : "input#cg_tags_box_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_tags_box_shadow_b_selector",
        'shadow_spread_selector' : "input#cg_tags_box_shadow_s_selector",
        'shadow_type_selector' : "select#cg_tags_box_shadow_t_selector"
    })
    
    activateTextShadowSelector ({
        'opacitySelector' : "input#cg_text_shadow_o_selector",
        'colorpicker_div' : "div#text_shadow_colorpicker",
        'colorpicker_input' : 'input#text_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_text_shadow_h_selector",
        'shadow_v_selector' : "input#cg_text_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_text_shadow_b_selector"
    });

    activateTextShadowSelector ({
        'opacitySelector' : "input#cg_tags_text_shadow_o_selector",
        'colorpicker_div' : "div#tags_text_shadow_colorpicker",
        'colorpicker_input' : 'input#tags_text_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_tags_text_shadow_h_selector",
        'shadow_v_selector' : "input#cg_tags_text_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_tags_text_shadow_b_selector"
    })


    activateBorderSelector ({
        'opacitySelector' : "input#seperator_border_o_selector",
        'colorpicker_div' : "div#seperator_border_colorpicker",
        'colorpicker_input' : 'input#seperator_border_colorpicker_input',
        'widthSelector' : "input#seperator_border_w_selector",
        'radiusSelector' : "input#seperator_margin_selector",
        'typeSelector' : "select#seperator_border_t_selector"
    })

    activateBorderSelector ({
        'opacitySelector' : "input#cg_icons_border_o_selector",
        'colorpicker_div' : "div#icons_border_colorpicker",
        'colorpicker_input' : 'input#icons_border_colorpicker_input',
        'widthSelector' : "input#cg_icons_border_w_selector",
        'radiusSelector' : "input#cg_icons_border_r_selector",
        'typeSelector' : "select#cg_icons_border_t_selector"
    })

    activateShadowSelector ({
        'opacitySelector' : "input#cg_tags_box_shadow_o_selector_hover",
        'colorpicker_div' : "div#tags_box_shadow_colorpicker_hover",
        'colorpicker_input' : 'input#tags_box_shadow_colorpicker_input_hover',
        'shadow_h_selector' : "input#cg_tags_box_shadow_h_selector_hover",
        'shadow_v_selector' : "input#cg_tags_box_shadow_v_selector_hover",
        'shadow_blur_selector' : "input#cg_tags_box_shadow_b_selector_hover",
        'shadow_spread_selector' : "input#cg_tags_box_shadow_s_selector_hover",
        'shadow_type_selector' : "select#cg_tags_box_shadow_t_selector_hover"
    });

    activateShadowSelector ({
        'opacitySelector' : "input#cg_icons_box_shadow_o_selector",
        'colorpicker_div' : "div#icons_box_shadow_colorpicker",
        'colorpicker_input' : 'input#icons_box_shadow_colorpicker_input',
        'shadow_h_selector' : "input#cg_icons_box_shadow_h_selector",
        'shadow_v_selector' : "input#cg_icons_box_shadow_v_selector",
        'shadow_blur_selector' : "input#cg_icons_box_shadow_b_selector",
        'shadow_spread_selector' : "input#cg_icons_box_shadow_s_selector",
        'shadow_type_selector' : "select#cg_icons_box_shadow_t_selector"
    });

    activateTextShadowSelector ({
        'opacitySelector' : "input#cg_tags_text_shadow_o_selector_hover",
        'colorpicker_div' : "div#tags_text_shadow_colorpicker_hover",
        'colorpicker_input' : 'input#tags_text_shadow_colorpicker_input_hover',
        'shadow_h_selector' : "input#cg_tags_text_shadow_h_selector_hover",
        'shadow_v_selector' : "input#cg_tags_text_shadow_v_selector_hover",
        'shadow_blur_selector' : "input#cg_tags_text_shadow_b_selector_hover"
    });

    activateTextOptionsSelector ({
        'opacitySelector': "input#cg_tags_text_o_selector_hover",
        'colorpicker_div': "div#tags_text_colorpicker_hover",
        'colorpicker_input': "input#tags_text_colorpicker_input_hover",
        'text_letter_spacing': "input#cg_tags_text_letter_spacing_hover",
        'text_word_spacing': "input#cg_tags_text_word_spacing_hover",
        'text_line_height': "input#cg_tags_text_line_height_hover",
        'text_size': "input#cg_tags_text_size_hover",
        'text_direction': "select#cg_tags_text_direction_hover",
        'text_unicode_bibi': "select#cg_tags_text_unicode_bibi_hover",
        'text_decoration': "select#cg_tags_text_decoration_hover",
        'text_transform': "select#cg_tags_text_transform_hover"
    })


    //border
    activateBorderSelector ({
        'opacitySelector' : "input#cg_image_border_o_selector_hover",
        'colorpicker_div' : "div#image_border_colorpicker_hover",
        'colorpicker_input' : 'input#image_border_colorpicker_input_hover',
        'widthSelector' : "input#cg_image_border_w_selector_hover",
        'radiusSelector' : "input#cg_image_border_r_selector_hover",
        'typeSelector' : "select#cg_image_border_t_selector_hover"
    })
    
    // shadow
    activateShadowSelector ({
        'opacitySelector' : "input#cg_image_box_shadow_o_selector_hover",
        'colorpicker_div' : "div#image_box_shadow_colorpicker_hover",
        'colorpicker_input' : 'input#image_box_shadow_colorpicker_input_hover',
        'shadow_h_selector' : "input#cg_image_box_shadow_h_selector_hover",
        'shadow_v_selector' : "input#cg_image_box_shadow_v_selector_hover",
        'shadow_blur_selector' : "input#cg_image_box_shadow_b_selector_hover",
        'shadow_spread_selector' : "input#cg_image_box_shadow_s_selector_hover",
        'shadow_type_selector' : "select#cg_image_box_shadow_t_selector_hover"
    })

    $("div#cg_img_anim_speed").slider({
            animate: "fast",
            max: 2000,
            min: 100,
            range: 'min',
            value: 1,
            create: function( event, ui ) { 
                var value = $(this).siblings('input').val();
                $(this).slider("value", value);
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms"); 
            },
            slide: function( event, ui ) { 
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms"); 
                viewPreview ();  
            },
            change: function( event, ui ) { 
                $(this).siblings('div.cg_img_size_selector_value').children('span').text($(this).slider("value") + "ms");
                $(this).siblings('input').val($(this).slider("value"));  
                viewPreview ();  
            }
    });
     $('div#cg_img_anim_speed').siblings('input').attr('data-div-id', 'cg_img_anim_speed');

    $('select#hover_type').on('change' , function(event) {
        event.preventDefault();
        viewPreview ();
    });

    $('div#cg_tag_state_selector').on('change', "input[type='radio']", function(event) {
        event.preventDefault();
        if ($(this).val()=='normal') {
            $('div#cg_tags_design_normal').show();
            $('div#cg_tags_design_hover').hide();
        } else if ($(this).val()=='hover') {
            $('div#cg_tags_design_normal').hide();
            $('div#cg_tags_design_hover').show();
        }
    }); 

    $('button#cg_style_load').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        // //console.log("load" + $('select#cg_predefined_styles').val());
        var id = $('select#cg_predefined_styles').val();
        if (id==='-Select-') {
            return;
        }
        //console.log(id);
        $('div#cg_main_caption').children('p').text('Loading Styles Please Wait...');
        $('div#cg_main_caption').show();
        stylesInterface.loadStyles(id, function(status, data){
            if (status=="success") {
                var styles = data[0];

                var viewId = styles.view_id;
                var hoverId = styles.hover_id;
                var margin = styles.margin;
                var thumbnailSize = styles.thumbnail_size;
                var thumbnailsCount = styles.thumbnails_count;

                var stylesNormal = styles.styles;
                var stylesImgHover = styles.styles_img_hover;
                var tagsHoverStyles = styles.tags_hover_styles;
            
                // setting viewId
                $('select#album_view').val(viewId);
                // setting hoverId
                $('select#hover_type').val(hoverId);
                // setting margin
                $('div#cg_img_margin_selector').slider("value", margin);
                // setting thumbnail size
                $('div#cg_img_size_selector').slider("value", thumbnailSize);
                // setting thumbnail count
                $('div#cg_img_count_selector').slider("value", thumbnailsCount);

                loadStyles (stylesNormal, 'tmp_styles');
                loadStyles (stylesImgHover, 'tmp_styles_img_hover');
                loadStyles (tagsHoverStyles, 'tmp_tags_hover_styles');
                
                viewPreview();
                $('div#cg_main_caption').hide();

            } else if (status=="error") {
                $('div#cg_main_caption').hide();
            }
        });
    });

    function loadStyles (stylesNormal, criteria) {
        var stylesArray = stylesNormal.split("|");
        for (var i = 0; i < stylesArray.length; i++) {
            var currentName = stylesArray[i].split(":")[0];
            var currentValue = stylesArray[i].split(":")[1];

            // //console.log(currentName + " "  + currentValue);
            var el = $("[name='cg_post[album]["+ criteria +"][" + currentName + "]'");
            var elId = el.attr('id');
            if (el[0].nodeName=='SELECT') {
                //console.log('SELECT#' + elId);
                $('SELECT#' + elId).val(currentValue);
                $('SELECT#' + elId).selectmenu();
                $('SELECT#' + elId).selectmenu('refresh');
            } else if (el[0].nodeName=='INPUT') {
                if (el.attr('data-rgb-r')!==undefined) {
                    //console.log('INPUT#'+ elId);
                    var div = $("div[data-output=" + elId + "]");
                    var op = $("input[data-output=" + elId + "]");
                    div.css('backgroundColor', currentValue);
                    op.val(parseFloat(currentValue.split(',')[3])*100);
                    op.spinner();
                    op.spinner('value', parseFloat(currentValue.split(',')[3])*100);
                    $('INPUT#'+ elId).val(currentValue);
                } else if (elId==undefined) {
                    var div = $("div#" + el.attr('data-div-id'));
                    el.val(currentValue);
                    div.slider();
                    div.slider("value", currentValue);
                } else {
                    //console.log('INPUT#'+ elId);
                    $('INPUT#'+ elId).val(currentValue);
                    $('INPUT#'+ elId).spinner();
                    $('INPUT#'+ elId).spinner('value', currentValue);    
                }
            }
        }
    }

    function saveStyles (mode, name) {
        var id = $('select#cg_predefined_styles').val();
        if ((id==='-Select-')&&(mode==='save')) {
            return;
        }
        $('div#cg_main_caption').children('p').text('Saving Styles Please Wait...');
        $('div#cg_main_caption').show();
        
        var viewId = $('select#album_view').val(),
            hoverId = $('select#hover_type').val(),
            thumbnailSize = parseInt($('div#cg_img_size_selector').slider("value")),
            thumbnailsCount = $('div#cg_img_count_selector').slider('value'),
            margin = parseInt($('div#cg_img_margin_selector').slider("value"));

        var styles = [];
        $("[name^='cg_post[album][tmp_styles]'").each(function(index, el) {
            var name = $(el).attr('name').slice($(el).attr('name').lastIndexOf('[')+1, $(el).attr('name').lastIndexOf(']'));
            var value = $(el).val();

            styles.push(name + ":" + value);
        });
        var style = styles.join("|");

        var tagsHoverStyles = [];
        $("[name^='cg_post[album][tmp_tags_hover_styles]'").each(function(index, el) {
            var name = $(el).attr('name').slice($(el).attr('name').lastIndexOf('[')+1, $(el).attr('name').lastIndexOf(']'));
            var value = $(el).val();

            tagsHoverStyles.push(name + ":" + value);
        });
        var tagsHoverStyle = tagsHoverStyles.join("|");

        var stylesImgHover = [];
        $("[name^='cg_post[album][tmp_styles_img_hover]'").each(function(index, el) {
            var name = $(el).attr('name').slice($(el).attr('name').lastIndexOf('[')+1, $(el).attr('name').lastIndexOf(']'));
            var value = $(el).val();

            stylesImgHover.push(name + ":" + value);
        });
        var styleImgHover = stylesImgHover.join("|");

        var savedStyles = {
            'view_id' : viewId,
            'hover_id' : hoverId,
            'thumbnail_size' : thumbnailSize,
            'thumbnails_count' : thumbnailsCount,
            'margin' : margin,
            'styles' : style,
            'styles_img_hover' : styleImgHover,
            'tags_hover_styles' : tagsHoverStyle
        }
        if (mode=="save") {
            stylesInterface.saveStyles(id, savedStyles, function(response) {
                if (response=="success") {
                    $('div#cg_main_caption').children('p').text('Styles Successfully Saved');
                    $('div#cg_main_caption').hide(); 
                } else if (response=="failed") {
                    $('div#cg_main_caption').children('p').text('Styles Saving Failed');
                    $('div#cg_main_caption').hide(); 
                }
                
            });
        } else if (mode=="saveAsStyles") {
            stylesInterface.saveAsStyles(id, savedStyles, name, function(response, id, name) {
                if (response=="success") {
                    $('input#cg_style_name').val("");
                    $('select#cg_predefined_styles').append("<option value=" + id + ">"+ name + "</option>").val(id);
                    $('div#cg_main_caption').children('p').text('Styles Successfully Saved');
                    $('div#cg_main_caption').hide(); 
                    $('input#cg_style_name').hide();
                    $('button#cg_confirm_button').off('click').hide();
                    $('button#cg_cancel_button').off('click').hide();
                } else if (response=="failed") {
                    $('div#cg_main_caption').children('p').text('Styles Saving Failed');
                    $('div#cg_main_caption').hide(); 
                    $('input#cg_style_name').hide();
                    $('button#cg_confirm_button').off('click').hide();
                    $('button#cg_cancel_button').off('click').hide();
                }
            });
        }
    }

    $('button#cg_style_save').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        saveStyles("save");
    });

    $('button#cg_style_save_as').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $('input#cg_style_name').show();

        $('button#cg_confirm_button').show().on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var value = $('input#cg_style_name').val();
            var pattern = /^[a-zA-Z_]+[a-zA-Z_0-9\s\-]*[a-zA-Z_0-9]+$/;
            // //console.log(pattern.test(value));
            if (pattern.test(value)) {
                $('input#cg_style_name').css('borderColor', '#AAAAAA');
                saveStyles("saveAsStyles", value);
            } else {
                $('input#cg_style_name').css('borderColor', 'red');
            }
        });
        $('button#cg_cancel_button').show().on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            $('input#cg_style_name').hide();
            $('button#cg_cancel_button').off('click').hide();
            $('button#cg_confirm_button').off('click').hide();
        });
    });

   
    $('div#cg_main_caption').hide();    
    
   

   tablecompanceate();


   
    
    ajaxCreate(album_id);
}