/**
 * Control tasks-show functions
 * @date 2014-06-17
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 *
 */
$(document).ready(function() {
	var mCheckBox = false;
	var mCheckBoxRequest = false;
	var mSelect = null;

	//Populate select(list) with status
	$.ajax({
		type : 'POST',
		url : '_control/tasks-show-c.php',
		dataType : "json",
		data : {
			getStatus : true
		},
		async : false,
		beforeSend : function() {
			showAlertMessage("Page is loading...", "info");
		}
	}).done(function(data) {
		if (data[0].erro == undefined) {

			var count;

			for ( count = 0; count < data.length; count++) {

				$('#selectTaskStatus').append($('<option>', {
					value : count,
					text : data[count]
				}));

			}

			$('#selectTaskStatus').append($('<option>', {
				value : count,
				text : "All"
			}));

		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	//Get the checkbox Request
	$("#checkTasksRequest").change(function() {
		//With this comparation, system do nothing when first run
		if (mCheckBoxRequest != ($(this).is(":checked"))) {
			$("#checkTasks").attr("checked", false);
			mCheckBox = false;

			mCheckBoxRequest = ($(this).is(":checked"));
			clearGrid();
			populateGrid(mSelect);
			refreshClickListeners();
		}
	}).trigger("change");

	//Get the checkbox Develop
	$("#checkTasks").change(function() {
		//With this comparation, system do nothing when first run
		if (mCheckBox != ($(this).is(":checked"))) {
			$("#checkTasksRequest").attr("checked", false);
			mCheckBoxRequest = false;

			mCheckBox = ($(this).is(":checked"));
			clearGrid();
			populateGrid(mSelect);
			refreshClickListeners();
		}
	}).trigger("change");

	//Get the actcual selected select option
	$("select").change(function() {
		$("select option:selected").each(function() {
			mSelect = $(this).index() + 1;
			//null represents user wants to see ALL datas
			if (mSelect == $("select option").size())
				mSelect = null;
		});
		clearGrid();
		populateGrid(mSelect);
		refreshClickListeners();
	}).trigger("change");

	//Clear grid with tasks
	function clearGrid() {
		$("#myTable tr:gt(1)").remove();
	}

	//Populate grid with tasks
	function populateGrid(taskStatus) {
		$.ajax({
			type : 'POST',
			url : '_control/tasks-show-c.php',
			dataType : "json",
			data : {
				getTasks : true,
				status : taskStatus,
				onlyMyTasks : mCheckBox,
				onlyMyTasksRequest : mCheckBoxRequest
			},
			async : false,
			beforeSend : function() {
				showAlertMessage("Page is loading...", "info");
			}
		}).done(function(data) {
			if (data[0].erro == undefined) {
				var tableNewLines = "";
				var isAdministrador = data[data.length - 1][0].administrator;
				var idUser = data[data.length - 1][0].idUser;
				var cronRun = data[data.length - 1].cronRun;
				var cronIdTask = data[data.length - 1].cronIdTask;

				for (var count = 0; count < data.length - 1; count++) {
					var taskOwner = false;
					var isDeveloper = false;
					var taskOpen = false;
					var isTestPhase = false;
					var isDevelopPhase = false;

					if (idUser == data[count].idUserCreation)
						taskOwner = true;

					if (idUser == data[count].idUserDevelop)
						isDeveloper = true;

					if (data[count].nameUserDevelop == "[OPEN]")
						taskOpen = true;

					if (data[count].statusName == "Test")
						isTestPhase = true;

					if (data[count].statusName == "Development")
						isDevelopPhase = true;

					tableNewLines += "<tr id=" + count + ">";

					tableNewLines += "<td>" + data[count].idTask + "</td>";
					tableNewLines += "<td>" + data[count].name + "</td>";
					tableNewLines += "<td>" + data[count].description + "</td>";
					tableNewLines += "<td>" + data[count].jobName + "</td>";
					tableNewLines += "<td>" + data[count].createDate + "</td>";
					tableNewLines += "<td>" + data[count].updateDate + "</td>";
					tableNewLines += "<td class='showBadge' data-id='" + data[count].idUserCreation + "' >" + data[count].nameUserCreation + "</td>";
					tableNewLines += "<td class='showBadge' data-id='" + data[count].idUserDevelop + "' >" + data[count].nameUserDevelop + "</td>";
					tableNewLines += "<td> [" + data[count].hoursPlanned + "] / [" + data[count].hoursReal + "]</td>";
					tableNewLines += "<td>" + data[count].statusName + "</td>";

					tableNewLines += "<td>";
					if (isAdministrador || taskOwner) {
						tableNewLines += "<a href='#' title='Edit'> <span class='glyphicon glyphicon-pencil' title='Edit'></span> </a>";
						tableNewLines += "<a href='#' title='Delet'> <span data-idline='" + count + "' data-idTask='" + data[count].idTask + "' class='glyphicon glyphicon-remove' title='Delet'></span> </a>";
					}

					if (isDeveloper && !isTestPhase) {
						tableNewLines += "<a href='#' title='Next Phase'> <span data-idline='" + count + "' data-idTask='" + data[count].idTask + "' class='glyphicon glyphicon-share-alt' title='Next Phase'></span> </a>";
					}

					if ((isDeveloper || isAdministrador) && !isTestPhase) {
						tableNewLines += "<a href='#' title='Transfer to another user'> <span data-iduser='" + data[count].idUserDevelop + "' data-name='" + data[count].name + "' data-idtask='" + data[count].idTask + "' class='glyphicon glyphicon-gift' data-toggle='modal' data-target='#modalTransfer' title='Transfer to another user'></span> </a>";
					}

					if (taskOpen) {
						tableNewLines += "<a href='#' title='Request to be developer'> <span data-id='" + data[count].idTask + "' class='glyphicon glyphicon-hand-up' title='Request to be developer'></span> </a>";
					}

					if (isTestPhase && (isDeveloper || isAdministrador)) {
						tableNewLines += "<a href='#' title='Return Development'> <span data-idline='" + count + "' data-idTask='" + data[count].idTask + "' class='glyphicon glyphicon-arrow-left' title='Return Development'></span> </a>";
						tableNewLines += "<a href='#' title='Edit testers'> <span data-name='" + data[count].name + "' data-id='" + data[count].idTask + "' data-idTask='" + data[count].idTask + "' class='glyphicon glyphicon-user'  data-toggle='modal' data-target='#modalTester' title='Edit testers'></span> </a>";
					}

					if (isDevelopPhase && isDeveloper) {
						if (cronRun) {
							if (cronIdTask == data[count].idTask)
								tableNewLines += "<a href='#' title='Stop working cron'> <span data-name='" + data[count].name + "' data-id='" + data[count].idTask + "' data-time = '" + data[count].hoursReal + "' class='glyphicon glyphicon-dashboard' title='Stop working cron'></span> </a>";
						} else
							tableNewLines += "<a href='#' title='Start working cron'> <span data-name='" + data[count].name + "' data-id='" + data[count].idTask + "' data-time = '" + data[count].hoursReal + "' class='glyphicon glyphicon-dashboard' title='Start working cron'></span> </a>";
					}

					tableNewLines += "<a href='#' title='comment'> <span id='commentbutton" + data[count].idTask + "'  data-line='" + count + "' data-idtask='" + data[count].idTask + "' data-iduserdevelop='" + data[count].idUserDevelop + "' class='glyphicon glyphicon-comment' title='Show Comments'></span> </a>";

					tableNewLines += "</td>";

					tableNewLines += "</tr>";
				}
				tableNewLines = $(tableNewLines);
				tableNewLines.insertAfter('#myTable tr:last');

			} else {
				showAlertMessage(data[0].erro, "info");
			}
		});
	}

	// Click Listeners
	function refreshClickListeners() {
		
		refreshBadges();
		
		//Read the actual filters to send a get method
		var getOnlyMy = "";
		var getStatusFilter = "";

		if ($("#checkTasks").is(":checked"))
			getOnlyMy = "onlyMy=2";
		if ($("#checkTasksRequest").is(":checked"))
			getOnlyMy = "onlyMy=1";

		getStatusFilter = "getStatusFilter=" + parseInt($("select option:selected").index());

		//Button Remove
		$("a").off("click", ".glyphicon-remove");
		$("a").on("click", ".glyphicon-remove", function() {
			var isComment = $(this).data("removecomment");
			var idTask = $(this).data("idtask");

			if (isComment == true) {
				//Delet Comment
				var idComment = $(this).data("idcomment");

				$.ajax({
					type : 'POST',
					url : '_control/tasks-show-c.php',
					dataType : "json",
					data : {
						deletComment : true,
						idComment : idComment
					},
					async : false,
					beforeSend : function() {
						showAlertMessage("Waiting server response...", "info");
					}
				}).done(function(data) {
					if (data[0].erro == undefined) {
						showAlertMessage("<a href='#' class='rollbackComment' data-idcomment = " + idComment + "> Comment  removed, if you want rollback, click here </a>", "warning");
						$("a").off("click", ".glyphicon-remove");
						refreshClickListeners();
						$("#commentbutton" + idTask).click();
					} else {
						showAlertMessage(data[0].erro, "danger");
					}
				});
			} else {
				//Delet Task
				var idLine = $(this).data("idline");

				$.ajax({
					type : 'POST',
					url : '_control/tasks-show-c.php',
					dataType : "json",
					data : {
						deletTask : true,
						idTask : idTask
					},
					async : false,
					beforeSend : function() {
						showAlertMessage("Waiting server response...", "info");
					}
				}).done(function(data) {
					if (data[0].erro == undefined) {
						showAlertMessage("<a href='#' class='rollback' data-idTask = " + idTask + "> Task removed, if you want rollback, click here </a>", "warning");
						$("table#myTable tr#" + idLine).remove();
						$("a").off("click", ".glyphicon-remove");
						refreshClickListeners();
					} else {
						showAlertMessage(data[0].erro, "danger");
					}
				});
			}
		});

		//Rollback Comment (cancel remove)
		$(".rollbackComment").off("click");
		$(".rollbackComment").on("click", function() {
			var idComment = $(this).data("idcomment");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					rollbackDeletComment : true,
					idComment : idComment
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Rollback task (cancel remove)
		$(".rollback").off("click");
		$(".rollback").on("click", function() {
			var idTask = $(this).data("idtask");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					rollbackDeletTask : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Rollback netx phase
		$(".rollbackNextPhase").off("click");
		$(".rollbackNextPhase").on("click", function() {
			var idTask = $(this).data("idtask");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					rollbackNextPhase : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Return Develop Phase
		$("a").off("click", ".glyphicon-arrow-left");
		$("a").on("click", ".glyphicon-arrow-left", function() {
			var idTask = ($(this).data("idtask"));
			var idLine = ($(this).data("idline"));

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					returnDevelopment : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				$("table#myTable tr#" + idLine).remove();
				refreshClickListeners();
			});
		});

		//Next Phase
		$("a").off("click", ".glyphicon-share-alt");
		$("a").on("click", ".glyphicon-share-alt", function() {
			var idTask = ($(this).data("idtask"));
			var idLine = ($(this).data("idline"));

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					nextPhase : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				showAlertMessage("<a href='#' class='rollbackNextPhase' data-idTask = " + idTask + "> Task in test phase, if you want rollback, click here </a>", "warning");
				$("table#myTable tr#" + idLine).remove();
				refreshClickListeners();
			});
		});

		//show comments
		$("a").off("click", ".glyphicon-comment");
		$("a").on("click", ".glyphicon-comment", function() {
			var idTask = ($(this).data("idtask"));
			var idComment = "comment" + ($(this).data("idtask"));
			var idLine = $(this).data("line");
			var idUserDevelop = $(this).data("iduserdevelop");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					getComments : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					//	showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					var comments = " ";
					var commentExists = document.getElementById(idComment);
					var idUser = data[data.length-1][0].idUser;

					// Create comment if not exists
					if (commentExists == null && commentExists != "") {

						comments += " <tr class='displayNone' id='" + idComment + "' > ";
						comments += " <td colspan='11'> ";

						for (var count = 0; count < data.length - 1; count++) {

							comments += "<div class='createBR'>";

							if (idUser == data[count].idUser)
								comments += "<a href='#' title='Delet'> <span data-removecomment='true' data-idcomment='" + data[count].idComment + "' data-idTask='" + data[count].idTask + "' class='glyphicon glyphicon-remove' title='Delet'></span> </a>";

							comments += data[count].dateComment + "::" + "[" + data[count].completeName + "] :: " + data[count].commentText;
							comments += "</div>";
							comments += "<br /> ";

						}

						comments += "<br/>";
						comments += "<div class='form-group'>";
						comments += "<textarea class='backgroundGray width100 resizeNone' id='textComment' rows='3' placeholder='Enter a comment'>";
						comments += "</textarea>";
						comments += "<button class='btn btn-default width100' type='submit' id='btnSubmitComment' data-iduserdevelop='" + idUserDevelop + "' data-idtask='" + idTask + "'> submit</button>";
						comments += "</div>";

						comments += " </td>";
						comments += "</tr>";

						comments = $(comments);
						comments.insertAfter("#" + idLine);
						
						$(".displayNone").fadeIn('slow');

						$("a").off("click", ".glyphicon-comment");
						refreshClickListeners();
					} else {
						// Remove comment if exists
						$("#" + idComment).fadeOut('slow', function() {
							$(this).remove();
							$("a").off("click", ".glyphicon-comment");
							refreshClickListeners();
						});
					}
				} else {
					if (data[0].erro == "No comments registred") {

						var comments = " ";
						var commentExists = document.getElementById(idComment);

						// Create comment if not exists
						if (commentExists == null && commentExists != "") {

							comments += " <tr class='displayNone' id='" + idComment + "' > ";
							comments += " <td colspan='11'> ";

							comments += "<div class='createBR'>";
							comments += "NO COMMENTS";
							comments += "</div>";
							comments += "<br /> ";

							comments += "<br/>";
							comments += "<div class='form-group'>";
							comments += "<textarea class='backgroundGray width100 resizeNone' id='textComment' rows='3' placeholder='Enter a comment'>";
							comments += "</textarea>";
							comments += "<button class='btn btn-default width100' type='submit' id='btnSubmitComment' data-iduserdevelop='" + idUserDevelop + "' data-idtask='" + idTask + "'> submit</button>";
							comments += "</div>";

							comments += " </td>";
							comments += "</tr>";

							comments = $(comments);
							comments.insertAfter("#" + idLine);
							$(".displayNone").fadeIn('slow');

							$("a").off("click", ".glyphicon-comment");
							refreshClickListeners();
						} else {
							// Remove comment if exists
							$("#" + idComment).fadeOut('slow', function() {
								$(this).remove();
								$("a").off("click", ".glyphicon-comment");
								refreshClickListeners();
							});
						}

					} else {
						showAlertMessage(data[0].erro, "info");
					}
				}
			});
		});

		//Button Submit comment
		$("#btnSubmitComment").off("click");
		$("#btnSubmitComment").on("click", function() {
			var idTask = $(this).data("idtask");
			var comment = $("#textComment").val();
			var idUserDevelop = $(this).data("iduserdevelop");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					postComment : true,
					idTask : idTask,
					idUserDevelop : idUserDevelop,
					comment : comment
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Button set testers
		$("a").off("click", ".glyphicon-user");
		$("a").on("click", ".glyphicon-user", function() {
			var idTask = $(this).data("idtask");
			var name = $(this).data("name");
			var users;

			//Get all users
			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					getAllUsersTesters : true,
				},
				async : false,
				beforeSend : function() {

				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					users = data;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});

			//Create content
			var contentPage = "";
			contentPage += "Task: #" + idTask + " - " + name;
			contentPage += "<br/>";
			contentPage += "Tester #1 ";
			contentPage += "<select id='selectTester1' class='selectpicker align-left'></select>";

			contentPage += "<br/>";
			contentPage += "Tester #2 ";
			contentPage += "<select id='selectTester2' class='selectpicker align-left'></select>";

			contentPage += "<br/>";
			contentPage += "Tester #3 ";
			contentPage += "<select id='selectTester3' class='selectpicker align-left'></select>";

			contentPage += "<br/>";
			contentPage += "Tester #4 ";
			contentPage += "<select id='selectTester4' class='selectpicker align-left'></select>";

			$("#modalTestText").html(contentPage);

			for (var count = 1; count < 5; count++) {
				$('#selectTester' + count).append($("<option>", {
					value : -1,
					text : "[OPEN]",
					"data-idtask" : idTask
				}));
			}
			for (var count = 0; count < users.length; count++) {
				//populate select #1
				$("#selectTester1").append($("<option>", {
					value : users[count].idUser,
					text : users[count].name + " " + users[count].lastName,
					"data-isregistred" : false,
					"data-idtask" : idTask
				}));
				//populate select #2
				$("#selectTester2").append($("<option>", {
					value : users[count].idUser,
					text : users[count].name + " " + users[count].lastName,
					"data-isregistred" : false,
					"data-idtask" : idTask
				}));
				//populate select #3
				$("#selectTester3").append($("<option>", {
					value : users[count].idUser,
					text : users[count].name + " " + users[count].lastName,
					"data-isregistred" : false,
					"data-idtask" : idTask
				}));
				//populate select #4
				$("#selectTester4").append($("<option>", {
					value : users[count].idUser,
					text : users[count].name + " " + users[count].lastName,
					"data-isregistred" : false,
					"data-idtask" : idTask
				}));
			}

			//Get users on test
			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					getTestersAtTask : true,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {

				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					for (var count = 0; count < data.length; count++) {
						$("#selectTester" + (count + 1)).val(data[count]);
						$("#selectTester" + (count + 1) + " option:selected").data("isregistred", true);
					}

					//PRECISA DAR UM APPEND DO ID USER, COM ELE PODE-SE SABER QUE JÁ EXISTE USUARIO CADASTRADO
				} else {
					//Ok, no have users on this test...continue...
				}
			});
		});

		//Button SAVE Testers
		$("#buttonSaveChangesTester").off("click");
		$("#buttonSaveChangesTester").on("click", function() {
			var idTask = ($("#selectTester1 option:selected").data("idtask"));

			var idUser1 = -1;
			var idUser2 = -1;
			var idUser3 = -1;
			var idUser4 = -1;

			if ($("#selectTester1 option:selected").data("isregistred") == false)
				idUser1 = ($("#selectTester1 option:selected").attr("value"));
			if ($("#selectTester2 option:selected").data("isregistred") == false)
				idUser2 = ($("#selectTester2 option:selected").attr("value"));
			if ($("#selectTester3 option:selected").data("isregistred") == false)
				idUser3 = ($("#selectTester3 option:selected").attr("value"));
			if ($("#selectTester4 option:selected").data("isregistred") == false)
				idUser4 = ($("#selectTester4 option:selected").attr("value"));

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					saveTesters : true,
					idUser1 : idUser1,
					idUser2 : idUser2,
					idUser3 : idUser3,
					idUser4 : idUser4,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Requesting...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Button transfer to another user
		$("a").off("click", ".glyphicon-gift");
		$("a").on("click", ".glyphicon-gift", function() {
			var idTask = $(this).data("idtask");
			var idUser = $(this).data("iduser");
			var name = $(this).data("name");
			var users;

			//Get all users
			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					getAllUsers : true,
					idUser : idUser
				},
				async : false,
				beforeSend : function() {

				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					users = data;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});

			//Create content
			var contentPage = "";
			contentPage += "Task: #" + idTask + " - " + name;
			contentPage += "</br>";
			contentPage += "Transfer To: ";
			contentPage += "<select id='selectTransfer' class='selectpicker align-left'></select>";
			$("#modalTransferText").html(contentPage);

			//populate select
			$('#selectTransfer').append($("<option>", {
				value : -1,
				text : "change to [OPEN]",
				"data-idtask" : idTask
			}));
			for (var count = 0; count < users.length; count++) {

				$("#selectTransfer").append($("<option>", {
					value : users[count].idUser,
					text : users[count].name + " " + users[count].lastName,
					"data-idtask" : idTask
				}));
			}
		});

		//Button SAVE Transfer to another user
		$("#buttonSaveChanges").off("click");
		$("#buttonSaveChanges").on("click", function() {
			var idUser = ($("#selectTransfer option:selected").attr("value"));
			var idTask = ($("#selectTransfer option:selected").data("idtask"));

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					transferAnotherUser : true,
					idUser : idUser,
					idTask : idTask
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Requesting...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Button Request to be developer
		$("a").off("click", ".glyphicon-hand-up");
		$("a").on("click", ".glyphicon-hand-up", function() {
			var id = $(this).data("id");

			$.ajax({
				type : 'POST',
				url : '_control/tasks-show-c.php',
				dataType : "json",
				data : {
					requestDeveloper : true,
					idTask : id
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Requesting...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Button Cron
		$("a").off("click", ".glyphicon-dashboard");
		$("a").on("click", ".glyphicon-dashboard", function() {
			var id = $(this).data("id");
			var name = $(this).data("name");
			var time = $(this).data("time");

			$.ajax({
				type : 'POST',
				url : '_control/page-cron-c.php',
				dataType : "json",
				data : {
					enableDisableCron : true,
					idTask : id,
					nameTask : name,
					timeTask : time
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Requesting cron...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					removeCookie("seconds");
					removeCookie("minutes");
					removeCookie("hours");
					window.location.href = "tasks-show.php?" + getOnlyMy + "&" + getStatusFilter;
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});
	}

	refreshClickListeners();

	//Look have pre settings do order
	if (getUrlVars()["getStatusFilter"] >= 0) {
		$("#selectTaskStatus").val(getUrlVars()["getStatusFilter"]);
		$("#selectTaskStatus").change();
	}
	if (getUrlVars()["onlyMy"] == 1) {
		$("#checkTasksRequest").attr("checked", true);
		$("#checkTasksRequest").change();
	} else {
		if (getUrlVars()["onlyMy"] == 2) {
			$("#checkTasks").attr("checked", true);
			$("#checkTasks").change();
		}
	}
});
