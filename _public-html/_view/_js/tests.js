/**
 * Control tests functions
 * @date 2014-06-17
 * @author Marcelo Belkiman
 */

$(document).ready(function() {
	var mCheckBox = true;
	var mSelect = null;

	//Populate select(list) with status
	$.ajax({
		type : 'POST',
		url : '_control/tests-c.php',
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

				$('#selectTestStatus').append($('<option>', {
					value : count,
					text : data[count]
				}));

			}

			$('#selectTestStatus').append($('<option>', {
				value : count,
				text : "All"
			}));

		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	//Get the checkbox
	$("#checkTest").change(function() {
		//With this comparation, system do nothing when first run
		if (mCheckBox != ($(this).is(":checked"))) {
			mCheckBox = ($(this).is(":checked"));

			clearGrid();
			populateGrid();
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
		populateGrid();
		refreshClickListeners();
	}).trigger("change");

	//Populate grid with tests
	function populateGrid() {
		$.ajax({
			type : 'POST',
			url : '_control/tests-c.php',
			dataType : "json",
			data : {
				getTests : true,
				needAnswering : mCheckBox,
				status : mSelect
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

				for (var count = 0; count < data.length - 1; count++) {
					var isTester = false;
					var isTaskCompleted = false;

					if ((idUser == data[count].testerId1) || (idUser == data[count].testerId2) || (idUser == data[count].testerId3) || (idUser == data[count].testerId4)) {
						isTester = true;
					}
					
					if(data[count].idTaskStatus == 4)
						isTaskCompleted = true;

					tableNewLines += "<tr id=" + count + ">";

					tableNewLines += "<td>" + data[count].idTask + "</td>";
					tableNewLines += "<td>" + data[count].description + "</td>";
					tableNewLines += "<td>" + data[count].testDate + "</td>";
					tableNewLines += "<td>" + data[count].testDateReady + "</td>";

					//=====START CONTROL COLOR OF STATUS TEST====
					switch(data[count].testerStatus1) {
						case 1:
							//Analysis
							tableNewLines += "<td class='testAnalysis'>" + data[count].testerName1 + "</td>";
							break;
						case 2:
							//Approved
							tableNewLines += "<td class='testApproved'>" + data[count].testerName1 + "</td>";
							break;
						case 3:
							//reproved
							tableNewLines += "<td class='testDisapproved'>" + data[count].testerName1 + "</td>";
							break;
						default:
							// ???
							tableNewLines += "<td>" + data[count].testerName1 + "</td>";
							break;
					}

					switch(data[count].testerStatus2) {
						case 1:
							//Analysis
							tableNewLines += "<td class='testAnalysis'>" + data[count].testerName2 + "</td>";
							break;
						case 2:
							//Approved
							tableNewLines += "<td class='testApproved'>" + data[count].testerName2 + "</td>";
							break;
						case 3:
							//reproved
							tableNewLines += "<td class='testDisapproved'>" + data[count].testerName2 + "</td>";
							break;
						default:
							// ???
							tableNewLines += "<td>" + data[count].testerName2 + "</td>";
							break;
					}

					switch(data[count].testerStatus3) {
						case 1:
							//Analysis
							tableNewLines += "<td class='testAnalysis'>" + data[count].testerName3 + "</td>";
							break;
						case 2:
							//Approved
							tableNewLines += "<td class='testApproved'>" + data[count].testerName3 + "</td>";
							break;
						case 3:
							//reproved
							tableNewLines += "<td class='testDisapproved'>" + data[count].testerName3 + "</td>";
							break;
						default:
							// ???
							tableNewLines += "<td>" + data[count].testerName3 + "</td>";
							break;
					}

					switch(data[count].testerStatus4) {
						case 1:
							//Analysis
							tableNewLines += "<td class='testAnalysis'>" + data[count].testerName4 + "</td>";
							break;
						case 2:
							//Approved
							tableNewLines += "<td class='testApproved'>" + data[count].testerName4 + "</td>";
							break;
						case 3:
							//reproved
							tableNewLines += "<td class='testDisapproved'>" + data[count].testerName4 + "</td>";
							break;
						default:
							// ???
							tableNewLines += "<td>" + data[count].testerName4 + "</td>";
							break;
					}

					switch(data[count].idTestStatus) {
						case "Analysis":
							tableNewLines += "<td class='testAnalysis'>" + data[count].idTestStatus + "</td>";
							break;
						case "Approved":
							tableNewLines += "<td class='testApproved'>" + data[count].idTestStatus + "</td>";
							break;
						case "Disapproved":
							tableNewLines += "<td class='testDisapproved'>" + data[count].idTestStatus + "</td>";
							break;
						default:
							// ???
							tableNewLines += "<td>" + data[count].idTestStatus + "</td>";
							break;
					}

					//=====FINAL CONTROL COLOR OF STATUS TEST====

					tableNewLines += "<td>";

					if (isTester && !isTaskCompleted) {
						tableNewLines += "<a href='#' title='reprove'> <span data-idline='" + count + "' data-idTest='" + data[count].idTest + "' class='glyphicon glyphicon-thumbs-down' title='Reprove'></span> </a>";
						tableNewLines += "<a href='#' title='analysis'> <span data-idline='" + count + "' data-idTest='" + data[count].idTest + "' class='glyphicon glyphicon-hand-right' title='Analysis'></span> </a>";
						tableNewLines += "<a href='#' title='approve'> <span data-idline='" + count + "' data-idTest='" + data[count].idTest + "' class='glyphicon glyphicon-thumbs-up' title='Approve'></span> </a>";
					}

					tableNewLines += "<a href='#' title='comment'> <span id='commentbutton" + data[count].idTest + "'  data-line='" + count + "' data-idTest='" + data[count].idTest + "' class='glyphicon glyphicon-comment' title='Show Comments'></span> </a>";

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
		//Disapproved
		$("a").off("click", ".glyphicon-thumbs-down");
		$("a").on("click", ".glyphicon-thumbs-down", function() {
			var idTest = ($(this).data("idtest"));
			var status = "Disapproved";
			setStatus(idTest, status);
		});

		//Approved
		$("a").off("click", ".glyphicon-thumbs-up");
		$("a").on("click", ".glyphicon-thumbs-up", function() {
			var idTest = ($(this).data("idtest"));
			var status = "Approved";
			setStatus(idTest, status);
		});

		//Analysis
		$("a").off("click", ".glyphicon-hand-right");
		$("a").on("click", ".glyphicon-hand-right", function() {
			var idTest = ($(this).data("idtest"));
			var status = "Analysis";
			setStatus(idTest, status);
		});

		//show comments
		$("a").off("click", ".glyphicon-comment");
		$("a").on("click", ".glyphicon-comment", function() {

			var idTest = ($(this).data("idtest"));
			var idComment = "comment" + ($(this).data("idtest"));
			var idLine = $(this).data("line");

			$.ajax({
				type : 'POST',
				url : '_control/tests-c.php',
				dataType : "json",
				data : {
					getComments : true,
					idTest : idTest
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
								comments += "<a href='#' title='Delet'> <span data-removecomment='true' data-idcomment='" + data[count].idComment + "' data-idtest='" + data[count].idTest + "' class='glyphicon glyphicon-remove' title='Delet'></span> </a>";

							comments += data[count].dateComment + "::" + "[" + data[count].completeName + "] :: " + data[count].commentText;
							comments += "</div>";
							comments += "<br /> ";

						}

						comments += "<br/>";
						comments += "<div class='form-group'>";
						comments += "<textarea class='backgroundGray width100 resizeNone' id='textComment' rows='3' placeholder='Enter a comment'>";
						comments += "</textarea>";
						comments += "<button class='btn btn-default width100' type='submit' id='btnSubmitComment' data-idtest='" + idTest + "'> submit</button>";
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
							comments += "<button class='btn btn-default width100' type='submit' id='btnSubmitComment' data-idtest='" + idTest + "'> submit</button>";
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
			var idTest = $(this).data("idtest");
			var comment = $("#textComment").val();

			$.ajax({
				type : 'POST',
				url : '_control/tests-c.php',
				dataType : "json",
				data : {
					postComment : true,
					idTest : idTest,
					comment : comment
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Waiting server response...", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					window.location.href = "tests.php?";
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});

		//Button Remove
		$("a").off("click", ".glyphicon-remove");
		$("a").on("click", ".glyphicon-remove", function() {
			var isComment = $(this).data("removecomment");
			var idTest = $(this).data("idtest");

			if (isComment == true) {
				//Delet Comment
				var idComment = $(this).data("idcomment");

				$.ajax({
					type : 'POST',
					url : '_control/tests-c.php',
					dataType : "json",
					data : {
						deletComment : true,
						idComment : idComment
					},
					async : false,
					beforeSend : function() {
						//showAlertMessage("Waiting server response...", "info");
					}
				}).done(function(data) {
					if (data[0].erro == undefined) {
						showAlertMessage("<a href='#' class='rollbackComment' data-idcomment = " + idComment + "> Comment  removed, if you want rollback, click here </a>", "warning");
						$("a").off("click", ".glyphicon-remove");
						refreshClickListeners();
						$("#commentbutton" + idTest).click();
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
				url : '_control/tests-c.php',
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
					window.location.href = "tests.php?";
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});
	}

	function setStatus(idTest, status) {
		$.ajax({
			type : 'POST',
			url : '_control/tests-c.php',
			dataType : "json",
			data : {
				setStatus : true,
				status : status,
				idTest : idTest
			},
			async : false,
			beforeSend : function() {
				showAlertMessage("Waiting server response...", "info");
			}
		}).done(function(data) {
			window.location.href = "tests.php?";
		});
	}

	//Clear grid
	function clearGrid() {
		$("#myTable tr:gt(1)").remove();
	}

});
