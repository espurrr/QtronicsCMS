// Creative Solutions Company
// Created by Samvel Antanyan (Creative Solutions Company)

function initTagManager ($) {

	///////////////////////////////////////// Functions ////////////////////////////////////
	function startTagManager () {
		if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
			var existingTagsList = $("div#cg_album_tags").find("div.tag").toArray();
			if (existingTagsList.length == 0) {
				$("div#add_tags_dialog").find("div.tagmanager_line").remove();
				getTags($("div#tagmanager_line_template"), "select", "album");
			} else {
				$("div#add_tags_dialog").find("div.tagmanager_line").remove();
				getTags($("div#tagmanager_line_template"), "add", "album");
			};
		} else {
			if ($('table.table-main').children('tbody').find(":checked").length==1) {
				var existingTagsList = $('table.table-main').children('tbody').find(":checked").parent("td").siblings('td.table-image-tags').find("div.tag").toArray();
				if (existingTagsList.length == 0) {
					$("div#add_tags_dialog").find("div.tagmanager_line").remove();
					getTags($("div#tagmanager_line_template"), "select", "image");
				} else {
					$("div#add_tags_dialog").find("div.tagmanager_line").remove();
					getTags($("div#tagmanager_line_template"), "add", "image");
				};
			} else {
				$("div#add_tags_dialog").find("div.tagmanager_line").remove();
				getTags($("div#tagmanager_line_template"), "select", "image");
			};
		}
	}



	// Ajax Request function
	function loadXMLDoc (url, cfunc) {
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=cfunc;
		xmlhttp.open ("GET", url, true);
		xmlhttp.send();
	}
	// get Tags form DB
	function getTags(obj, mode, type) {
		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=tagmanage&format=json" + "&req=get" + "&type=" + type, function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			var data = JSON.parse(xmlhttp.responseText);
    			if (type == "album") {
	    			if (mode=="select") {
	    				renderTags ( obj, data, type );	
	    			} else if (mode=="list") {
	    				renderTagsList ( obj, data);
	    			} else if (mode=="update") {
	    				updateTags ( obj, data, type );
	    			} else if (mode=="add") {
	    				addExistingTags ( obj, data, type );
	    			}
    			} else if (type == "image"){
    				if (mode=="select") {
	    				renderTags ( obj, data, type );	
	    			} else if (mode=="list") {
	    				renderTagsList ( obj, data);
	    			} else if (mode=="update") {
	    				updateTags ( obj, data, type );
	    			} else if (mode=="add") {
	    				addExistingTags ( obj, data, type );
	    			}
    			};
    		} 
		});
	}
	
	// remove Tags form DB
	function removeTag (tag_id, type) {
		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=tagmanage&format=json" + "&req=remove" + "&type=" + type + "&tag_id=" + tag_id , function() {
		});
		$('table.table-main').find("span[data-id=" + tag_id + "]").parent('div.tag').remove();
	}

	// Edit Tag Names form DB
	function editTag(tag_id, tag_name, type) {
		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=tagmanage&format=json" + "&req=update" + "&type=" + type + "&tag_id=" + tag_id + "&tag_name=" + tag_name, function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			if (xmlhttp.responseText=="OK") {
    				getTags($("div#tagmanager_manage_template"), "list", type);
    				$("div#new_tag_dialog").dialog("close");
    			} else {
    				$("div#new_tag_dialog").find("input").css('border', '1px solid red').siblings('span').text('Tag with this name already exists');
    			};
    		} 
		});
	}

	// Add New Tag
	function newTag (tag_name, type) {
		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=tagmanage&format=json" + "&req=new" + "&type=" + type + "&tag_name=" + tag_name, function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			if (xmlhttp.responseText=="OK") {
    				getTags($("div#tagmanager_manage_template"), "list", type);
    				$("div#new_tag_dialog").dialog("close");
    			} else {
    				$("div#new_tag_dialog").find("input").css('border', '1px solid red').siblings('span').text('Tag with this name already exists');
    			};
    		} 
		});
	}

	// Render Tags Select
	function renderTags (obj, data, type) {
		var newObject = obj.clone().show().removeAttr("id").addClass('tagmanager_line'),
			container = obj.parent("div");
			var option0 = document.createElement("option");
				option0.value = "-1";
				if (type=="album") {
					option0.innerHTML = "Select Album Tag";
				} else if (type=="image") {
					option0.innerHTML = "Select Image Tag";
				};
				newObject.find("select").append(option0);
			for (var i = 0; i < data.length; i++) {
				var option = document.createElement("option");
				option.value = data[i]['id'];
				option.innerHTML = data[i]['name'];
				newObject.find("select").append(option);
			};
			container.append(newObject);
	}



	// Update Tags Select
	function updateTags (obj, data, type) {
		
		var container = obj.parents("div#add_tags_part"),
			ListOfTags = container.find("div.tagmanager_line").toArray();
			for (var i = 0; i < ListOfTags.length; i++) {
				currentSelected = $(ListOfTags[i]).find(":selected").val();
				currentSelect = $(ListOfTags[i]).find("select");
				currentSelect.html("");
				var option0 = document.createElement("option");
				option0.value = "-1";
				if (type=="album") {
					option0.innerHTML = "Select Album Tag";
				} else if (type=="image") {
					option0.innerHTML = "Select Image Tag";
				};
				currentSelect.append(option0);
				for (var i = 0; i < data.length; i++) {
					var option = document.createElement("option");
					option.value = data[i]['id'];
					option.innerHTML = data[i]['name'];
					currentSelect.append(option);
				};
				currentSelect.val(currentSelected);
			};
	}

	function addExistingTags (obj, data, type) {
		var container = obj.parents("div#add_tags_part"),
			ListOfTags;
			if (type=="album") {
				ListOfTags = $("div#cg_album_tags").find("div.tag").toArray();		
			} else if (type=="image") {
				ListOfTags = $('table.table-main').children('tbody').find(":checked").parent("td").siblings('td.table-image-tags').find("div.tag").toArray();
			};
			for (var i = 0; i < ListOfTags.length; i++) {
				var newObject = obj.clone().show().removeAttr("id").addClass('tagmanager_line'),
				currentSelected = $(ListOfTags[i]).find("span").attr("data-id");
				var option0 = document.createElement("option");
				option0.value = "-1";
				if (type=="album") {
					option0.innerHTML = "Select Album Tag";
				} else if (type=="image") {
					option0.innerHTML = "Select Image Tag";
				};
				newObject.find("select").append(option0);
				for (var j = 0; j < data.length; j++) {
					var option = document.createElement("option");
					option.value = data[j]['id'];
					option.innerHTML = data[j]['name'];
					newObject.find("select").append(option);
				};
				newObject.find("select").val(currentSelected);

				container.append(newObject);
			};
	}

	// Render Tags List (Manager)
	function renderTagsList(obj, data) {
		container = obj.parent("div");
		container.find("div.tagmanager_line").remove();
		for (var i = 0; i < data.length; i++) {

			var newObject = obj.clone().show().removeAttr("id").addClass('tagmanager_line');
			newObject.find("span").html(data[i]['name']).data("id", data[i]['id']);
			container.append(newObject);
		};
	}


	///////////////////////////////////////// Dialogs ////////////////////////////////////
	$("div#tags_confirm_dialog").dialog( {
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
                    	   	var tag_id = $("div#manage_tags_part").find('.toRemove').find("span").data("id");
                        	$("div#manage_tags_part").find('.toRemove').remove();
                        if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                        	removeTag(tag_id, "album");
                        } else {
                        	removeTag(tag_id, "image");
                        };
                        $(this).dialog("close");
                    }
                }, {
                    text: "Cancel",
                    click: function() {
                        $(this).dialog("close");
                }
            }],
            close : function () {
            	$("div#manage_tags_part").find('.toRemove').removeClass('toRemove');
            }
            
            });
	$("div#new_tag_dialog").dialog( {
            height  : 200, 
            width   : 400, 
            modal   : true, 
            autoOpen    : false,
            show    : { effect : "slideDown", duration : 400 }, 
            hide    : { effect: "slideUp", duration: 400 },
            buttons : [ {
                text: "Ok",
                    click: function() {
                    	if ($(this).dialog("option", "title") == "New Tag") {
                    		var tag_name = $("div#new_tag_dialog").find("input").val(),
                    		pattern = /^[a-z\_]+[a-z0-9.\-_#$\s\(\)@!]*$/i;
                    		if (pattern.test(tag_name)) {
                    			if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                    				newTag(tag_name, "album");	
                    			} else {
                    				newTag(tag_name, "image");
                    			}
                    		} else {
                    			$("div#new_tag_dialog").find("input").css('border', '1px solid red').siblings('span').text('Tag Name Must Be Alpanumeric, Dash and Underscore');
                    		}
                    	} else {
                    		var tag_id = $("div#manage_tags_part").find('.toEdit').find("span").data("id"),
                    		tag_name = $("div#new_tag_dialog").find("input").val(),
                    		tag_name_old = $("div#new_tag_dialog").find("input").data("value");
                    		if (tag_name==tag_name_old) {
                    			$(this).dialog("close");
                    		} else {
                    			var pattern = /^[a-z\_]+[a-z0-9.\-_#$\s\(\)@!]*$/i;
                    			if (pattern.test(tag_name)) {
                    				if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                    					editTag(tag_id, tag_name, "album");	
                    				} else {
                    					editTag(tag_id, tag_name, "image");	
                    				}
                    				
                    			} else {
                    				$("div#new_tag_dialog").find("input").css('border', '1px solid red').siblings('span').text('Tag Name Must Be Alpanumeric, Dash and Underscore');
                    			};
                    		};
                    	}	
                    }
                }, {
                    text: "Cancel",
                    click: function() {
                        $(this).dialog("close");
                }
            }],
            close : function () {
            	$("div#new_tag_dialog").find("input").css('border', '').siblings('span').text('');
            	$("div#manage_tags_part").find('.toEdit').removeClass('toEdit');
            }
            
            });

	///////////////////////////////////////// Event Listners ////////////////////////////////////

	// Go To Tag Manager

	$("div#add_tags_dialog").on('click', "img[title='manage']", function(event) {
		event.preventDefault();
		$("div#add_tags_part").animate({
			opacity: 0},
			300, 
			function() {
				$("div#add_tags_part").hide();
				if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                    getTags($("div#tagmanager_manage_template"), "list", "album");
                } else {
                	getTags($("div#tagmanager_manage_template"), "list", "image");
                }
				$("div#manage_tags_part").show();
				$("div#manage_tags_part").animate({
					opacity: 1},
					300);
		});
	});	

	// Go To Tag Select

	$("div#add_tags_dialog").on('click', "img[title='back']", function(event) {
		event.preventDefault();
		$("div#manage_tags_part").animate({
			opacity: 0},
			300, 
			function() {
				$("div#manage_tags_part").hide();
				$("div#add_tags_part").show();
				if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
                    getTags($("div#tagmanager_line_template"), "update", "album");
                } else {
                	getTags($("div#tagmanager_line_template"), "update", "image");
                }
				$("div#add_tags_part").animate({
					opacity: 1},
					300);
		});
	});	

	// add line in tag select section

	$("div#add_tags_part").on('click', "img[title='more']", function(event) {
		event.preventDefault();
		if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
            getTags($("div#tagmanager_line_template"), "select", "album");
        } else {
           	getTags($("div#tagmanager_line_template"), "select", "image");
        }
	});

	// remove line in tag select section
	$("div#add_tags_part").on('click', "img[title='remove']", function(event) {
		event.preventDefault();
		//console.log("click");
		$(this).parents("div.tagmanager_line").remove();
		if ($("div#add_tags_part").find("div.tagmanager_line").length==0) {
			if ($("div#add_tags_dialog").dialog("option", "title") == "Add Album Tags") {
            	getTags($("div#tagmanager_line_template"), "select", "album");
        	} else {
           		getTags($("div#tagmanager_line_template"), "select", "image");
        	}
			//console.log("bla");
		};
	});

	// add tag in tag manager section
	$("div#manage_tags_part").on('click', "img[title='remove']", function(event) {
		event.preventDefault();
		$(this).parents("div.tagmanager_line").addClass('toRemove');
		$("div#tags_confirm_dialog").dialog("open");
	});

	// edit tag in tag manager section
	$("div#manage_tags_part").on('click', "img[title='edit']", function(event) {
		event.preventDefault();
		$(this).parents("div.tagmanager_line").addClass('toEdit');
		var tag_name = $("div#manage_tags_part").find('.toEdit').find("span").html();
		$("div#new_tag_dialog").find("input").val(tag_name).data("value", tag_name);
		$("div#new_tag_dialog").dialog("option", "title", "Edit Tag");
		$("div#new_tag_dialog").dialog("open");
	});

	// add tag in tag manager section
	$("div#manage_tags_part").on('click', "img[title='New']", function(event) {
		event.preventDefault();
		//$(this).parents("div.tagmanager_line").addClass('toEdit');
		//var tag_name = $("div#manage_tags_part").find('.toEdit').find("span").html();
		$("div#new_tag_dialog").find("input").attr("placeholder", "Enter Tag Name").val("");
		$("div#new_tag_dialog").dialog("option", "title", "New Tag");
		$("div#new_tag_dialog").dialog("open");
	});



	startTagManager ();
	
}

function deInitTagManager ($) {
	$("div#add_tags_dialog").off("click");
	$("div#add_tags_part").off("click");
	$("div#manage_tags_part").off("click");
}