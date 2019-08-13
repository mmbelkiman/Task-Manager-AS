/**
 * Control home functions
 * @date 2014-06-10
 * @author Marcelo Belkiman
 */

$(document).ready(function() {
	var remindesNeedSave = false;

	$("textarea").change(function() {
		remindesNeedSave = true;
	});

	//Get commit not uploaded
	$.ajax({
		type : "POST",
		url : "_control/home-c.php",
		dataType : "json",
		data : {
			getCommits : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			var tableNewLines = "";

			for ( count = 0; count < data.length; count++) {
				tableNewLines += "<tr>";
				tableNewLines += "<td>" + data[count].idCommit + "</td>";
				tableNewLines += "<td>" + data[count].name + "</td>";
				tableNewLines += "<td>" + data[count].files + "</td>";
				tableNewLines += "<td>" + data[count].dateUpdate + "</td>";
				tableNewLines += "<td><a href='#' title='Commit'> <span id='commitButton' data-idcommit='" + data[count].idCommit + "' class='glyphicon glyphicon-upload' title='Commit'></span> </a></td>";
				tableNewLines += "</tr>";
			}
			tableNewLines = $(tableNewLines);
			tableNewLines.insertAfter("#myTable tr:last");
		}
	});

	//Action of commit upload
	$("a").on("click", ".glyphicon-upload", function() {
		idCommit = $(this).data("idcommit");

		$.ajax({
			type : "POST",
			url : "_control/home-c.php",
			dataType : "json",
			data : {
				uploadCommits : true,
				idCommit : idCommit
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				window.location.href = "home.php";
			} else {
				showAlertMessage(data[0].error, "danger");
			}
		});
	});

	//Get count tasks develop
	$.ajax({
		type : "POST",
		url : "_control/home-c.php",
		dataType : "json",
		data : {
			getCountTasksDevelop : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			$(".container .tasks-develop p").html("<p>You have " + data[0] + " tasks in progress.</p>");
		}
	});

	//Get count tasks analyse
	$.ajax({
		type : "POST",
		url : "_control/home-c.php",
		dataType : "json",
		data : {
			getCountTasksAnalyse : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			$(".container .tasks-analyse").html("<p>You have " + data[0] + " tests to do.</p>");
		}
	});

	//Get count tasks open
	$.ajax({
		type : 'POST',
		url : '_control/home-c.php',
		dataType : "json",
		data : {
			getCountTasksOpen : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			$(".container .tasks-open").html("<p>Has " + data[0] + " open tasks you can do.</p>");
		}
	});

	//Show tasks approved
	$.ajax({
		type : 'POST',
		url : '_control/home-c.php',
		dataType : "json",
		data : {
			getTasksApproval : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			tableNewLines = "";
			for ( count = 0; count < data.length; count = count + 2) {
				$(".container .tasks-approval-disapproval").append("<p class='commitTaskApproved' data-idtask=\"" + data[count] + "\"><a href='#' title='Task: " + data[count + 1] + "' style='color:#00CC00;' data-toggle='modal' data-target='#modalFiles'>Your task  \"" + data[count + 1] + "\"' was approved, click here to confirm to the next commit.</a></p>");
			}

		}
	});

	//Commit tasks Approved
	$(".commitTaskApproved").on("click", function() {
		$("#modalIdTask").val($(this).data("idtask"));
	});

	$("#buttonSaveFiles").on("click", function() {
		idTask = $("#modalIdTask").val();
		files = $("#describeFiles").val();
		$.ajax({
			type : "POST",
			url : "_control/home-c.php",
			dataType : "json",
			data : {
				insertCommits : true,
				idTask : idTask,
				files : files
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				window.location.href = "home.php";
			} else {
				showAlertMessage(data[0].error, "danger");
			}
		});
	});

	//Show tasks not approved
	$.ajax({
		type : 'POST',
		url : '_control/home-c.php',
		dataType : "json",
		data : {
			getTasksDisapproval : true
		},
		async : false
	}).done(function(data) {
		if (data[0].error == undefined) {
			for ( count = 0; count < data.length; count++) {
				$(".container .tasks-approval-disapproval").append("<p style=\"color:#FF3333;\">Your task \"" + data[count] + "\" wasn't approved.</p>");
			}
		}
	});

	//Load user reminders on open page
	$.ajax({
		type : 'POST',
		url : '_control/home-c.php',
		dataType : "json",
		data : {
			getReminders : true
		},
		async : false,
		beforeSend : function() {

		}
	}).done(function(data) {
		if (data[0].error == undefined) {
			$("#textReminders").html(data);
		} else {
			showAlertMessage(data[0].error, "danger");
		}
	});

	//User logout ou exit the current page, try save your remindes
	$("#logout").click(function() {
		setReminders();
	});
	$(window).unload(function() {
		setReminders();
	});

	//Save user reminders on the exit page
	function setReminders() {
		if (remindesNeedSave == 1) {
			var mReminders = document.getElementById("textReminders").value;

			$.ajax({
				type : 'POST',
				url : '_control/home-c.php',
				dataType : "json",
				data : {
					setReminders : true,
					reminders : mReminders
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Saving yours reminders...", "warning");
				}
			}).done(function(data) {
				if (data[0].error == undefined) {

				} else {
					showAlertMessage(data[0].error, "danger");
				}
			});
		}
	}

});
