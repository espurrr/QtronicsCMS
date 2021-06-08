/// Created by Samvel Antanyan (Creative Solutions Company)###



function initFileManager($, base_path) {
	/// Defaults ###
	var path = base_path + "/media/com_creativegallery/home";
	var output_id = "responsebox";
	var directory_tree = "ul#directory_tree";

	//Buttons
	var home_button = "button#cg_home";
	var up_button = "button#cg_up";
	var mkdir_button = "button#cg_make_dir";
	var select_button = "button#cg_select_all"; 
	var deselect_button = "button#cg_deselect_all"; 
	var rename_button = "button#cg_rename"; 
	var remove_button = "button#cg_remove"; 
	var copy_button = "button#cg_copy";
	var cut_button = "button#cg_cut";
	var paste_button = "button#cg_paste";
	var grid_button = "button#cg_grid";
	var list_button = "button#cg_list";

	var clipboard = "empty";
	var old_path = "";
	var new_path = "";
	var cut_mode = "off";
	var file_extension; 
	var start_rename = false;
	var error_count = 0;

	// var extensions_list = [];
	// 	extensions_list[1] = "IMAGETYPE_GIF";
	// 	extensions_list[2] = "IMAGETYPE_JPEG";
	// 	extensions_list[3] = "IMAGETYPE_PNG";
		
	document.getElementById(output_id).setAttribute("data-path_main", path);
	/////////////////////////////////////////////////////////////	
	////////////////////////// Functions ////////////////////////
	/////////////////////////////////////////////////////////////	

	// Ajax request funtion
	function loadXMLDoc (url, cfunc) {
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=cfunc;
		xmlhttp.open ("GET", url, true);
		xmlhttp.send();
	}

	// get directory hierarchical content 
	function getDir(dir, rename_trigger) {

		if (typeof dir == "undefined") {
			dir = path;
		}
		document.getElementById(output_id).setAttribute("data-path_cur", dir);

		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=scandir&format=json" + "&dir=" + dir, function() {
			$("div.windows8").show();
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			try {
    				var obj = JSON.parse(xmlhttp.responseText);	
    				$("div.windows8").hide();
    				renderHtml ( obj );	
    			} catch (error) {
    				error_count ++;
    				if (error_count<=5) {
    					getDir(dir, rename_trigger);	
    				} else {
    					text = document.createElement("H1");
						text.innerHTML = "Something goes wrong please contact us for support" ;
						$("div.windows8").hide();
						$("ul#" + output_id).html(text);
    				};
    			}
    			if (typeof rename_trigger != "undefined") {
    				//console.log(rename_trigger);
    				current_content = $("li[data-path='" + rename_trigger + "']").addClass("selected");
    				renameObj(current_content, "start");
    			}
    		} 
		})
	}

	// directiory requests from php
	function directory(dir, request, name, new_name) {
		//console.log("dir:" + dir +" request:" + request + " name:" + name);
		loadXMLDoc("./index.php?option=com_creativegallery&view=creativeajax&layout=directory&format=json" + "&dir=" + dir + "&req=" + request + "&name=" + name + "&new_name=" + new_name, function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			resp = JSON.parse(xmlhttp.responseText);
    			if (request == "mkdir") {
    				error_count =0 ;
    				//console.log(resp.name + " " + resp.status);
    				if (resp.name!=0) {
    					getDir(dir, resp.name);	
    				};
    			} else {
    				error_count = 0;
    				getDir(dir);
    			}
    			
			}
		});
	}

	function copy_ajax (old_p, new_p, arr, cut_mode_trig) {
		var request = "./index.php?option=com_creativegallery&view=creativeajax&layout=directory&format=json&req=copy&old_path=" + old_p + "&new_path=" + new_p +"&cut_mode=" + cut_mode_trig + "&number=" + arr.length;
		for (i=0; i<arr.length; i++) {
			request = request + "&item" + i + "=" + arr[i];
		}
		loadXMLDoc (request, function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
    			error_count = 0;
    			getDir(new_p);
    		}
		})

	}
	// filemanager page renderer
	function renderHtml (content) {
		var doc = document.createDocumentFragment();
		if (typeof content !== 'undefined' && content.length > 0) {
			var possition = 0;
			for (i=0; i<content.length; i++) {
				if (content[i].type =="file") { continue; }
				var input_field = document.createElement("INPUT");
					input_field.setAttribute("type", "text");
					input_field.className = "cg_hidden input_field";
				var li = document.createElement("LI");
					li.setAttribute("data-path", content[i].path)
				var anc = document.createElement("A");
					anc.rel = content[i].path;
					anc.title = content[i].path;	
				var icon = document.createElement("IMG");
					icon.className = "icon_filemanager"
				var contentName = document.createElement("SPAN");
					contentName.className="name";
					contentName.innerHTML = content[i].name;
				var contentDetails = document.createElement("SPAN");
					contentDetails.className="details";
				if (content[i].type =="folder") {
					li.setAttribute("data-type", "folder");
					li.setAttribute("data-pos", possition);
					possition++;
					icon.setAttribute("src", base_path + "/administrator/components/com_creativegallery/assets/css/filemanager/img/folder.png");	
					if (content[i].size == 0) {
						contentDetails.innerHTML = "empty";
					} else if(content[i].size == 1) {
						contentDetails.innerHTML = content[i].size + " item";
					} else {
						contentDetails.innerHTML = content[i].size + " items";
					}
					anc.appendChild(icon);
					anc.appendChild(contentName);
					anc.appendChild(input_field);
					anc.appendChild(contentDetails);
					li.appendChild(anc);
					doc.appendChild (li);
				}
			}
			for (i=0; i<content.length; i++) {
				if (content[i].type =="folder") { continue; }
				if (content[i].imagethumb == 0) { continue; }
				var input_field = document.createElement("INPUT");
					input_field.setAttribute("type", "text");
					input_field.className = "cg_hidden input_field";
					var li = document.createElement("LI");
					li.setAttribute("data-path", content[i].path);
				var anc = document.createElement("A");
					anc.rel = content[i].path;
					anc.title = content[i].path;	
				var icon = document.createElement("IMG");
					icon.className = "icon_file"
				var contentName = document.createElement("SPAN");
					contentName.className="name";
					contentName.innerHTML = content[i].name;
				var contentDetails = document.createElement("SPAN");
					contentDetails.className="details";
				if (content[i].type =="file") {
					li.setAttribute("data-pos", possition);
					possition++;
					li.setAttribute("data-type", "file");
					icon.setAttribute("src", content[i].imagethumb);
					//console.log(content[i].imgheight + " " + content[i].imgwidth);
					icon.style.marginTop = (60 - Math.min(content[i].imgheight, content[i].imgwidth)/3 )/2 + "px";
					contentDetails.innerHTML = bytesToSize (content[i].size);
					anc.appendChild(icon);
					anc.appendChild(contentName);
					anc.appendChild(input_field);
					anc.appendChild(contentDetails);
					li.appendChild(anc);
					doc.appendChild (li);
				}
			}
		}
		else {
			text = document.createElement("H1");
			text.innerHTML = "directory is empty" ;
			doc.appendChild(text);
		}
		//Redering Directory Tree
		var dir_tree = document.createDocumentFragment();
		var current_path = document.getElementById(output_id).getAttribute("data-path_cur");
		var current_path_arr = current_path.split("/");
		var isDirectoryHidden = true;
		for (i=0; i<current_path_arr.length; i++) {
			if (current_path_arr[i]=='com_creativegallery') {
				isDirectoryHidden = false;
				continue;
			}
			if (!isDirectoryHidden) {
				var new_path =  current_path_arr.slice(0, i*1+1).join("/");
				var tree_li = document.createElement("LI");
						tree_li.setAttribute("data-path",new_path);
						tree_li.innerHTML = current_path_arr[i];
						tree_li.className="path_element"
						dir_tree.appendChild(tree_li);
				};
			}
			// Rendering paste button functionality
		$(directory_tree).html(dir_tree);
		$("ul#" + output_id).html(doc);

	}

 	// Markers adding function
 	function addMarker (obj) {
 		var mark = document.createElement("IMG");
			mark.setAttribute("src", base_path + "/administrator/components/com_creativegallery/assets/css/filemanager/img/mark.png");
			mark.className = "mark";
			obj.append(mark);
 	}
 	// rename funtion
 	function renameObj (obj , phase ) {
 		var name_field = obj.find("span.name");
 		var input_field = obj.find("input.input_field");
 		if (phase == "start")
 		{
 			var cover = document.createElement("DIV");
 			cover.className = "cover";
 			$("ul#" + output_id).append(cover);
 			obj.addClass("renaming");
 			var value = name_field.html();
 			if (obj.attr("data-type")=="file") {
 				var arr = value.split(".");
 				file_extension = arr[arr.length-1];
 				value = arr.slice(0, arr.length-1).join(".");
 			};
 			input_field.attr("value", value);
 			name_field.addClass("cg_hidden");
 			input_field.show();
 			input_field.select();
 			start_rename = true;
 		} else {
 			var old_value = name_field.html();
 			var value = input_field.val();
 			//var pattern = /^\w+[\s+\w+]*$/i;
 			//var pattern = /^(?!\.)(?!com[0-9]$)(?!con$)(?!lpt[0-9]$)(?!nul$)(?!prn$)[^\|\*\?\\:<>/$"]*[^\.\|\*\?\\:<>/$"]+$/i;
 			var pattern = /^[a-z\_]+[a-z0-9.\-_#$\s\(\)@!]*$/i;
 			if (pattern.test(value)) {
 				start_rename = false;
 				$("ul#" + output_id + ">div.cover").remove();
 				obj.removeClass("renaming");
 				if (obj.attr("data-type")=="file") {
 					value = value + "." + file_extension;	
 				}
 				name_field.html(value);
 				input_field.hide();
 				name_field.removeClass("cg_hidden");
 				var current_path = document.getElementById(output_id).getAttribute("data-path_cur");
 				directory (current_path, "rename", old_value, value);
  			} else {
  				input_field.css("border-color", "red");
 				var hint = document.createElement("SPAN");
 				hint.innerHTML = "Invalid Name";
 				hint.className = "hint";
 				obj.append(hint);
 			}
 			
 		}
 	}

 	function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return '0 Bytes';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}

	function copyDir (elems, cut_mode_trigger) {
		if (typeof cut_mode_trigger=="undefined") {
			cut_mode_trigger = "off";
		}
		var arrayOfPathsToCopy = [];
		for (i=0; i<elems.length; i++) {
			var arr_path = elems[i].getAttribute("data-path").split("/");
			arrayOfPathsToCopy.push( arr_path[arr_path.length-1]);
		}
		//console.log(old_path + " " + new_path + " " + arrayOfPathsToCopy);
		copy_ajax (old_path, new_path, arrayOfPathsToCopy, cut_mode_trigger);
	}

	function copyButtonPress () {
		clipboard = $("ul#"+ output_id +">li.selected").toArray();
		if (clipboard.length != 0) {
			old_path = document.getElementById(output_id).getAttribute("data-path_cur");
			$(paste_button).addClass ("paste-ready");
		} 
	}

	function cutButtonPress () {
		clipboard = $("ul#"+ output_id +">li.selected").toArray();
		if (clipboard.length != 0) {
			old_path = document.getElementById(output_id).getAttribute("data-path_cur");
			$(paste_button).addClass ("paste-ready");
			$("ul#"+ output_id +">li.selected").addClass("cut-ready");
			cut_mode = "on";
		} 
	}

	function pasteButtonPress () {
		new_path = document.getElementById(output_id).getAttribute("data-path_cur");
		$("ul#"+ output_id +">li").removeClass("cut-ready");
		$(paste_button).removeClass ("paste-ready");
		if (cut_mode == "on") {
			if (old_path === new_path) {
				cut_mode = "off";	
			} else {
				copyDir(clipboard, cut_mode);	
				cut_mode = "off";
			}			
		} else {
			copyDir(clipboard, cut_mode);	
			cut_mode = "off";
		}
	}

	function selecAllButtonPressed () {
		var all_buttons = $('ul#' + output_id + ">li" );
	 	all_buttons.addClass("selected");
	 	addMarker(all_buttons);
	}

	function deselecAllButtonPressed () {
		var all_buttons = $('ul#' + output_id + ">li" );
	 	all_buttons.removeClass("selected");
	 	$("img.mark").remove();
	}

	function mkdirButtonPressed() {
		var current_path = document.getElementById(output_id).getAttribute("data-path_cur");
 		directory (current_path, "mkdir", "new folder", "new");
	}

	function renameButtonPressed () {
		var object = $("ul#"+ output_id +">li.selected").first();
		if (object.length!=0) {
			renameObj(object, "start");	
		}
	}

	function removeButtonPressed() {
		var current_path = document.getElementById(output_id).getAttribute("data-path_cur");
 		var objects = $("ul#"+ output_id +">li.selected").toArray();
 		for (i=0; i<objects.length; i++) {
 			directory (current_path, "remove", $(objects[i]).find("span.name").html());
 		}
	}
	function add_marker (obj) {
			//console.log(obj);
			if (ctrlPressed) {
				if (obj.hasClass("selected")) {
					obj.removeClass("selected");
					obj.children("img.mark").remove();
				} else {
					obj.addClass("selected");	
					lastClickPossition = obj.attr("data-pos");
					addMarker(obj);
				}	
			} else if (shiftPressed) {
				secClickPossition = obj.attr("data-pos")*1;
				var selectStart,selectEnd;
				if (lastClickPossition < secClickPossition) {
					selectStart = lastClickPossition;
					selectEnd = secClickPossition;
				} else {
					selectStart = secClickPossition;
					selectEnd = lastClickPossition;
				}
				//console.log(selectStart + " " + selectEnd);
				for (var i = selectStart; i <= selectEnd; i++) {
					var current_obj = $("li[data-pos='" + i + "']").addClass("selected");
					addMarker(current_obj);
				};
			} else {
				obj.siblings("li.selected").removeClass("selected");
				$("img.mark").remove();
				if (obj.hasClass("selected")) {
					obj.removeClass("selected");
					lastClickPossition = 0;
				} else {
					obj.addClass("selected");	
					lastClickPossition = obj.attr("data-pos");
					addMarker(obj);
				}
			}	
		}

	/////////////////////////////////////////
	/////////// Event Listners///////////////
	/////////////////////////////////////////

 	//Ctrl Key detection part
	var ctrlPressed = false;
	var shiftPressed = false;
	
	$(window).keydown(function(evt) {
  			if (evt.which == 17) { // ctrl
  				evt.preventDefault();
    			ctrlPressed = true;
  			} else if (evt.which == 16) {
  				evt.preventDefault();
  				shiftPressed = true;
  			}
		}).keyup(function(evt) {
  			if (evt.which == 17) { // ctrl
    			ctrlPressed = false;
  			} else if (evt.which == 16) {
  				shiftPressed = false;
  			}
		});

	// Enter Key Press detection
	$(window).keydown(function(e) {
		if((e.which == 13)&&(start_rename==true))  {
    		var object = $("ul#"+ output_id +">li.selected").first();
        	renameObj(object, "end");
        }
	});

	// Ctrl + C, CTRL + V, CTRL +X functionality

	$(window).keydown(function (evt) {
		if (ctrlPressed==true) {
			if ( evt.which == 67 ) { //Copy
				evt.preventDefault();
				copyButtonPress (); 
			} else if (evt.which == 88) { //Cut
				evt.preventDefault();
				cutButtonPress (); 
			} else if (evt.which == 86) {  // Paste
				evt.preventDefault();
				pasteButtonPress (); 
			} else if (evt.which == 65) { //select All.
				evt.preventDefault();
				selecAllButtonPressed ();
			} else if (evt.which == 68) {
				evt.preventDefault();
				deselecAllButtonPressed ();
			} 
		} else {
			if ( evt.which == 113 ) {
				evt.preventDefault();
				renameButtonPressed ();
			} else if ( evt.which == 46 ) {
				evt.preventDefault();
				removeButtonPressed();
			} else if (evt.which==27) {
				evt.preventDefault();
				deselecAllButtonPressed ();
			}
		}
	})

	// Select_all button funtionality
	$(select_button).off('click');
	$(select_button).on('click', function () {
	 	selecAllButtonPressed ();
	 })

	// Deselect_all button funtionality
	$(deselect_button).off('click');
	$(deselect_button).on('click', function () {
	 	deselecAllButtonPressed ();
	 })


	// Mkdir button funtionality 
	$(mkdir_button).off("click");
 	$(mkdir_button).on("click", function() {
 		 mkdirButtonPressed();
 	})
 	// remove funtionality
 	$(remove_button).off("click");
 	$(remove_button).on("click", function() {
 		removeButtonPressed();
 	})
 	
 	// Rename button funtionality
 	$(rename_button).off("click");
	$(rename_button).on("click", function () {
		renameButtonPressed ();
	})


 	// Home Button funtionality
	$(home_button).off("click");
	$(home_button).on("click", function () {
		error_count = 0;
		getDir ();
	});

	// Up Button Functionaly
	$(up_button).off("click");
	$(up_button).on("click", function () {
		var current_path = document.getElementById(output_id).getAttribute("data-path_cur");
		if (current_path === path) {
			error_count = 0;
			getDir(path);
		} else {
			var path_array = current_path.split("/");
			error_count = 0;
			getDir(path_array.slice(0, path_array.length-1).join("/"));			
		}
	});

	// Double Click funtionality
	$("ul#" + output_id).off('dblclick');
	$("ul#" + output_id).on('dblclick', "li" , function () {
		if (this.getAttribute("data-type")=="folder") {
			error_count = 0;
			getDir(this.getAttribute("data-path"));
		};
		//console.log(this.getAttribute("data-path"));
	})	

	// Select Functionality
	var lastClickPossition = 0;
	$("ul#" + output_id).off('click');
	$("ul#" + output_id).on('click', "li" , function () {
		var obj = $(this);
		// console.log("click");
		setTimeout (add_marker(obj), 200);
	})

	// Directory Tree funtionality
	$(directory_tree).off('click');
	$(directory_tree).on('click', "li.path_element" , function () {
		error_count = 0;
		getDir(this.getAttribute("data-path"));
	})

	// Copy Functionality
	$(copy_button).off('click');

	$(copy_button).on('click', function () {
		copyButtonPress();
	});
	// Cut Functionality
	$(cut_button).off('click');
	$(cut_button).on('click', function () {
		cutButtonPress ();
	})
	// Paste Functionality
	$(paste_button).off('click');
	$(paste_button).on('click', function () {
		pasteButtonPress ()	
	})
	$(grid_button).off('click');
	$(grid_button).on('click', function() {
		$("ul#" + output_id + ">li").removeClass("list");
	})
	$(list_button).off('click');
	$(list_button).on('click', function() {
		$("ul#" + output_id + ">li").addClass("list");
	})
	error_count = 0;
	getDir ();
}

function deInitFileManager($) {
	var output_id = "responsebox";
	var home_button = "button#cg_home";
	var up_button = "button#cg_up";
	var mkdir_button = "button#cg_make_dir";
	var select_button = "button#cg_select_all"; 
	var deselect_button = "button#cg_deselect_all"; 
	var rename_button = "button#cg_rename"; 
	var remove_button = "button#cg_remove"; 
	var copy_button = "button#cg_copy";
	var cut_button = "button#cg_cut";
	var paste_button = "button#cg_paste";
	var grid_button = "button#cg_grid";
	var list_button = "button#cg_list";

	
	$(window).off('keydown');
	$(window).off('keyup');
	$(select_button).off('click');
	$(deselect_button).off('click');
	$(mkdir_button).off("click");
	$(remove_button).off("click");
	$(rename_button).off("click");
	$(home_button).off("click");
	$(up_button).off("click");
	$("ul#" + output_id).off('dblclick');
	$("ul#" + output_id).off('click');
	$(directory_tree).off('click');
	$(copy_button).off('click');
	$(cut_button).off('click');
	$(paste_button).off('click');
	$(grid_button).off('click');
	$(list_button).off('click');
}
