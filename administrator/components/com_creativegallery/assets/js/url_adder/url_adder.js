function initUrlAdder ($) {
	$("div#weblink_wrapper").on('click', 'span.weblink_add' , function(event) {
		event.preventDefault();
		IsValidImageUrl(this);
	});
	$("div#weblink_wrapper").on('click', "span.weblink_more" , function(event) {
		event.preventDefault();
		newLine() ;
	});


	function newLine() {
		var elem = $("div#weblink_line_template").clone().show().attr("id", "");
		$("div#weblink_wrapper").append(elem);
	}

	function IsValidImageUrl(data) {
		var urlInput = $(data).parents("div.weblink_line").find("input[name='url']");
		var nameInputVal = $(data).parents("div.weblink_line").find("input[name='name']").val();
		var urlVal = urlInput.val();
		var img = new Image();
    	img.onerror = function() { 
    		urlInput.css("border", "1px solid red");
		}; 
    	img.onload = function() { 
    		$(data).removeClass('weblink_add').removeClass('weblink_button_anim').addClass('weblink_added')
		 	.children('img').attr("src", "components/com_creativegallery/assets/images/icons/checked_checkbox.png")
			.siblings('span').html('added');
			urlInput.css("border", "1px solid green"); 
			if ($("span.ui-dialog-title").html()=="Add Preview Image") {
				$("img#cg_preview_image").attr("src", urlVal).siblings('input').val(urlVal);
			} else {
				imgToTable(urlVal, nameInputVal);	
			};
			$(data).parents("div.weblink_line").children('div.weblink_part').first().remove();
			$(data).parents("div.weblink_line").children('div.weblink_part').first().remove();
			var div = $("<div>", {"class" : "weblink_part"}).append(img);
			$(data).parents("div.weblink_line").prepend(div);
		};
    	img.src = urlVal;
    	img.className = "weblink_thumb";
	}

	function imgToTable(url, name) {
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
            tr.id="item_0";
        var img_name_input = document.createElement("INPUT");
            img_name_input.type="hidden";
            img_name_input.value = url;
            img_name_input.name = "cg_post["+ "item_" + 0 + "][path]";
            thumbnail_img.src = url;
            thumbnail_img.alt = name;
            thumbnail_img.style.maxWidth = "144px";
            thumbnail_img.style.maxHeight = "144px";
            td3.appendChild(thumbnail_img);
            td3.appendChild(img_name_input);
            td3.className = "table-thumbnail";
            name_input.type="text";
            name_input.className = "cg_image_name";
            name_input.value = name;
            name_input.name = "cg_post["+ "item_" + 0 + "][name]";
            title_input.type="text";
            title_input.className = "cg_image_title";
            title_input.value = name;
            title_input.name = "cg_post["+ "item_" + 0 + "][title]";
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
            link_input.value ="Link";
            link_input.name = "cg_post[item_0][link]";
            link_div.appendChild(link_span);
            link_div.appendChild(link_input);

            var target_select = document.createElement("SELECT"),
                target_div = document.createElement("DIV"),
                target_span = document.createElement("SPAN");
            target_span.innerHTML = "Target:";
            target_select.className="cg_image_select";
            target_select.value="0";
            target_select.name = "cg_post["+ "item_0][target]";
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
            description_textarea.value = "";
            description_textarea.className="table-image-description";
            description_textarea.name = "cg_post["+ "item_0][description]";
            td5.appendChild(description_textarea);
            td5.className = "table-title";
            //tags_span.innerHTML = "No Tags";
            var tags_input = document.createElement("input");
            tags_input.type = "hidden";
            tags_input.value = "";
            tags_input.name = "cg_post["+ "item_0][tags]";
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
            publish_img_input.name = "cg_post["+ "item_0][publish]";
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
	newLine();
}