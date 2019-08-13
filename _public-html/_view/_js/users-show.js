/**
 * Control user-show functions
 * @date 2014-06-17
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 *
 */
$(document).ready(function() {
	//Populate grid with users
	$.ajax({
		type : 'POST',
		url : '_control/users-show-c.php',
		dataType : "json",
		data : {
			getUsers : true
		},
		async : false,
		beforeSend : function() {
			showAlertMessage("Page is loading...", "info");
		}
	}).done(function(data) {
		if (data[0].erro == undefined) {
			var tableNewLines = "";
			var isAdministrador = data[data.length - 1].isAdministrator;

			for (var count = 0; count < data.length - 1; count++) {
				var mJobs = "";

				for (var countJob = 0; countJob < data[count].jobs.length; countJob++) {
					mJobs += data[count].jobs[countJob].job + " ; ";
				}
				mJobs = mJobs.substring(0, mJobs.length - 3);

				tableNewLines += "<tr>";

				tableNewLines += "<td>" + data[count].idUser + "</td>";
				tableNewLines += "<td> </td>";
				tableNewLines += "<td>" + data[count].name + " " + data[count].lastName + "</td>";
				tableNewLines += "<td>" + mJobs + "</td>";
				tableNewLines += "<td>" + data[count].username + "</td>";

				tableNewLines += "<td>";
				if (isAdministrador) {
					tableNewLines += "<a href='#' title='Edit' > <span id='" + data[count].idUser + "' class='glyphicon glyphicon-pencil' title='Edit'></span> </a>";

					if (data[count].activeUser == 1)//User active
						tableNewLines += "<a href='#' title='disable'> <span id='" + data[count].idUser + "' class='glyphicon glyphicon-remove' title='Disable'></span> </a>";
					else
						tableNewLines += "<a href='#' title='enable'> <span id='" + data[count].idUser + "' class='glyphicon glyphicon-ok' title='Enable'></span> </a>";
				}
				tableNewLines += "</td>";

				tableNewLines += "</tr>";
			}
			tableNewLines = $(tableNewLines);
			tableNewLines.insertAfter('#myTable tr:last');

		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	// Click Listeners
	$("a").on("click", ".glyphicon-remove", function() {
		var id = $(this).attr("id");
		$.ajax({
			type : 'POST',
			url : '_control/users-show-c.php',
			dataType : "json",
			data : {
				disableUser : true,
				idUser : id
			},
			async : false,
			beforeSend : function() {
				showAlertMessage("Saving changes...", "warning");
			}
		}).done(function(data) {
			if (data[0].erro == undefined) {
				$("#" + id).removeClass();
				$("#" + id).addClass("glyphicon").addClass("glyphicon-ok");
				$("#" + id).prop('title', 'Enable');
			} else {
				showAlertMessage(data[0].erro, "danger");
			}
		});
	});

	$("a").on('click', ".glyphicon-ok", function() {
		var id = $(this).attr("id");

		$.ajax({
			type : 'POST',
			url : '_control/users-show-c.php',
			dataType : "json",
			data : {
				enableUser : true,
				idUser : id
			},
			async : false,
			beforeSend : function() {
				showAlertMessage("Saving changes...", "warning");
			}
		}).done(function(data) {
			if (data[0].erro == undefined) {
				$("#" + id).removeClass();
				$("#" + id).addClass("glyphicon").addClass("glyphicon-remove");
				$("#" + id).prop('title', 'Disable');
			} else {
				showAlertMessage(data[0].erro, "danger");
			}
		});
	});
	
	//Edit user
	$("a").on("click",".glyphicon-pencil", function() {
		var id = $(this).attr("id");
		window.location.href = "users-registration.php?id="+id;
	});
});
