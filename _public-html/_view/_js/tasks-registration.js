/**
 * Control task-registration functions
 * @date 2014-06-26
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 */

type = "text/javascript" > $(function() {
	$('#datetimepicker3').datetimepicker({
		pickDate : false
	});
});

$(document).ready(function() {
	//Validate each field
	$(".validate-field").focusout(function() {
		idField = $(this).attr("id");
		validateAllFields(idField);
	});

	//Populate the checkboxes with jobs
	$.ajax({
		type : 'POST',
		url : '_control/tasks-registration-c.php',
		dataType : "json",
		data : {
			getJobs : true
		},
		async : false,
		beforeSend : function() {
			showAlertMessage("Page is loading...", "info");
		}
	}).done(function(data) {
		if (data[0].error == undefined) {
			var strHtmlJobs = "";
			for (var count = 0; count < data.length; count++) {
				strHtmlJobs += "<option value=" + data[count].idJob + ">" + data[count].job + "</option>";
			};
			$("#selectJobs").html(strHtmlJobs);
		} else {
			showAlertMessage(data[0].error, "danger");
		}
	});

	$("#btnSubmit").click(function() {
		//Validate all fields
		var countErrors = 0;
		if (validateAllFields("taskName") == false) {
			countErrors++;
		}
		if (validateAllFields("taskDatetime") == false) {
			countErrors++;
		}

		//None error
		if (countErrors == 0) {
			var taskName = $("#taskName").val();
			var taskDescription = $("#taskDescription").val();
			var taskMainJob = $(".selectpicker option:selected").val();
			var taskDatetime = $("#datetimepicker3 input").val();

			$.ajax({
				type : "POST",
				url : "_control/tasks-registration-c.php",
				dataType : "json",
				data : {
					insertTask : true,
					name : taskName,
					description : taskDescription,
					idJob : taskMainJob,
					datetime : taskDatetime
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Page is loading...", "info");
				}
			}).done(function(data) {
				if (data[0].error == undefined) {
					showAlertMessage("Task create with success.", "success");
					window.location.href = "tasks-registration.php";
				} else {
					showAlertMessage(data[0].error, "danger");
				}
			});
		} else {
			showAlertMessage("Incorrect fields, please fill correctly.", "danger");
		}

	});

	//Validate all fields
	function validateAllFields(idField) {
		//Declare variable with current field
		var resultReturn = false;
		var selectField = idField;
		var valueField = $("#" + selectField).val();

		$.ajax({
			type : 'POST',
			url : '_control/tasks-registration-c.php',
			dataType : 'json',
			data : {
				validateAllUserFields : true,
				selectField : selectField,
				valueField : valueField
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				$("#" + selectField).tooltip("disable");
				$("#" + selectField).removeClass("error-validate");
				resultReturn = true;
			} else {
				$("#" + selectField).addClass("error-validate");
				$("#" + selectField).tooltip().attr("data-original-title", data[0].error);
				$("#" + selectField).tooltip("enable");
				resultReturn = false;
			}

		});

		return resultReturn;
	}

});
