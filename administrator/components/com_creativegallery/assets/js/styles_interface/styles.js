(function ($) {
	window.stylesInterface = {};

	stylesInterface.loadStyles = function (id, func) {
		$.ajax({
			url: "./index.php?option=com_creativegallery&view=creativeajax&layout=stylesmanage&format=json&req=get&id=" + id,
			type: 'GET'
		})
		.done(function(data) {
			func ('success', data);
		})
		.fail(function(data) {
			func ('error', data);
		})
		.always(function(data) {
			//func ('complete', data);
		});
	}

	stylesInterface.saveStyles = function (id, info, func) {
		var url = "./index.php?option=com_creativegallery&view=creativeajax&layout=stylesmanage&format=json&req=save&id=" + id;
		// console.log(info);
		var keys = Object.keys(info);
		for (var i = 0; i < keys.length; i++) {
			url = url + '&' + keys[i] + '=' + info[keys[i]];
		}
		
		$.ajax({
			url: url,
			type: 'GET'
		})
		.done(function(data) {
			func(data.responseText);
		})
		.fail(function(data) {
			func(data.responseText);
		})
	}

	stylesInterface.saveAsStyles = function (id, info, name, func) {
		var url = "./index.php?option=com_creativegallery&view=creativeajax&layout=stylesmanage&format=json&req=saveas&name=" + name;

		var keys = Object.keys(info);
		for (var i = 0; i < keys.length; i++) {
			url = url + '&' + keys[i] + '=' + info[keys[i]];
		}

		$.ajax({
			url: url,
			type: 'GET'
		})
		.done(function(data) {
			func('success', data, name);
		})
		.fail(function(data) {
			func('failed', data, name);
		})
	}

})(creativeSolutionsjQuery)