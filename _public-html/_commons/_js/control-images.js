/*
 * Pass the image on _temp directory to final directory.
 * filename : name of file (whithout extesion)
 * idUser : if you pass that, save at passed idUser, without, save at active user
 */
function saveImages(filename, idUser) {

	if ( typeof (idUser) === "undefined") {
		idUser = -1;
	}

	$(document).ready(function() {
		$.ajax({
			type : "POST",
			url : '_commons/_php/control-images.php?saveDir',
			dataType : "json",
			data : {
				filename : filename,
				idUser : idUser
			},
			async : false,
			beforeSend : function() {

			}
		}).done(function(data) {
			if (data[0].error == undefined)
				updateImageDatabase(filename, data[0].filename, idUser);
		});
	});
}

function updateImageDatabase(filename, filenameFull, idUser) {
	$(document).ready(function() {
		$.ajax({
			type : 'POST',
			url : '_commons/_php/control-images.php?updateDatabase',
			dataType : 'json',
			data : {
				filename : filename,
				filenameFull : filenameFull,
				idUser : idUser
			},
			async : false,
			beforeSend : function() {

			}
		}).done(function(data) {

		});
	});
}

function uploadImages(event) {
	$(document).ready(function() {
		event.stopPropagation();
		event.preventDefault();

		var data = new FormData();
		$.each(files, function(key, value) {
			data.append(key, value);
		});

		$.ajax({
			url : '_commons/_php/control-images.php?saveTemp',
			type : 'POST',
			data : data,
			cache : false,
			dataType : 'json',
			processData : false, // Don't process the files
			contentType : false, // Set content type to false as jQuery will tell the server its a query string request
			async : false,
			beforeSend : function() {
				showAlertMessage("Uploading image on server...", "warning");
			}
		}).done(function(data) {
			if (data[0].error == undefined) {
				showAlertMessage("Upload ok...", "info");
				setCookie("uploadFile", data[0].files, "1");
			} else {
				showAlertMessage(data[0].error, "danger");
			}
		});
	});
}