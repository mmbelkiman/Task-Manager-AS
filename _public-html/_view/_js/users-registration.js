/**
 * Control user-registration functions
 * @date 2014-06-17
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 *
 */
$(document).ready(function() {

	//this code will run after _commons/js/contol-images.js...when show the uploaded image
	$('input[type=file]').on('change', function(event) {
		files = event.target.files;
		uploadImages(event);
		if (getCookie("uploadFile") != "") {
			$("#imageUser").attr("src", "_images/_temp/" + getCookie("uploadFile") + "?" + new Date().getTime());
			removeCookie("uploadFile");
		}
	});

	//Populate the checkboxes with jobs
	$.ajax({
		type : 'POST',
		url : '_control/users-registration-c.php',
		dataType : "json",
		data : {
			getJobs : true
		},
		async : false,
		beforeSend : function() {
			showAlertMessage("Page is loading...", "info");
		}
	}).done(function(data) {
		if (data[0].erro == undefined) {
			var strHtmlJobs = "";
			for (var count = 0; count < data.length; count++) {
				strHtmlJobs += "<input class='chkJob' type='checkbox' name='" + data[count].job + "' value='" + data[count].idJob + "'>" + data[count].job + "</input><br />";
			};
			$("#chkJobs").html(strHtmlJobs);
		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	//If contains user id populate with your datas
	if (getUrlVars()["id"] != "") {
		idUser = getUrlVars()["id"];
		$.ajax({
			type : 'POST',
			url : '_control/users-registration-c.php',
			dataType : 'json',
			data : {
				getUserById : true,
				idUser : idUser
			},
			async : false
		}).done(function(data) {
			for ( count = 0; count < data[0].jobs.length; count++) {
				$("#chkJobs .chkJob[value='" + data[0].jobs[count].idJob + "']").prop("checked", true);
			}

			if (data[0].photo.trim() != null && data[0].photo.trim() != "")
				$("#imageUser").attr("src", "_images/_" + idUser + "/" + data[0].photo);

			$("#firstNameRegistration").val(data[0].name);
			$("#lastNameRegistration").val(data[0].lastName);
			$("#emailRegistration").val(data[0].email);
			$("#usernameRegistration").val(data[0].username);
			$("#secretQuestionRegistration").val(data[0].secretQuestion);
		});
	}

	//Validate each field
	$(".validate-field").focusout(function() {
		//Declare variable with current field
		var selectField = $(this).attr("id");
		var valueField = $(this).val();
		var confirmPasswordsField = $("#passwordRegistration").val();
		var confirmPasswordsAgainField = $("#passwordAgainRegistration").val();
		var idUser = getUrlVars()["id"];

		$.ajax({
			type : 'POST',
			url : '_control/users-registration-c.php',
			dataType : 'json',
			data : {
				validateAllUserFields : true,
				selectField : selectField,
				valueField : valueField,
				confirmPasswordsField : confirmPasswordsField,
				confirmPasswordsAgainField : confirmPasswordsAgainField,
				idUser : idUser
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				$("#" + selectField).tooltip("disable");
				$("#" + selectField).removeClass("error-validate");
				if (selectField == "passwordRegistration" || selectField == "passwordAgainRegistration") {
					$("#passwordRegistration").removeClass("error-validate");
					$("#passwordAgainRegistration").removeClass("error-validate");
					$("#passwordRegistration").tooltip("disable");
					$("#passwordAgainRegistration").tooltip("disable");
				}
			} else {
				$("#" + selectField).addClass("error-validate");
				$("#" + selectField).tooltip().attr("data-original-title", data[0].error);
				$("#" + selectField).tooltip("enable");
			}

		});
	});

	//Validate all fields
	function validateAllFields(idField) {
		//Declare variable with current field
		var resultReturn = false;
		var selectField = idField;
		var valueField = $("#" + idField).val();
		var confirmPasswordsField = $("#passwordRegistration").val();
		var confirmPasswordsAgainField = $("#passwordAgainRegistration").val();
		var idUser = getUrlVars()["id"];

		$.ajax({
			type : 'POST',
			url : '_control/users-registration-c.php',
			dataType : 'json',
			data : {
				validateAllUserFields : true,
				selectField : selectField,
				valueField : valueField,
				confirmPasswordsField : confirmPasswordsField,
				confirmPasswordsAgainField : confirmPasswordsAgainField,
				idUser : idUser
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				$("#" + selectField).tooltip("disable");
				$("#" + selectField).removeClass("error-validate");
				if (selectField == "passwordRegistration" || selectField == "passwordAgainRegistration") {
					$("#passwordRegistration").removeClass("error-validate");
					$("#passwordAgainRegistration").removeClass("error-validate");
					$("#passwordRegistration").tooltip("disable");
					$("#passwordAgainRegistration").tooltip("disable");
				}
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

	//Create new user
	$("#btnSubmit").click(function() {
		//Validate all fields
		var countErrors = 0;
		if (validateAllFields("firstNameRegistration") == false) {
			countErrors++;
		}
		if (validateAllFields("lastNameRegistration") == false) {
			countErrors++;
		}
		if (validateAllFields("emailRegistration") == false) {
			countErrors++;
		}
		if (validateAllFields("usernameRegistration") == false) {
			countErrors++;
		}
		if (validateAllFields("passwordRegistration") == false) {
			countErrors++;
		}
		if (validateAllFields("passwordAgainRegistration") == false) {
			countErrors++;
		}
		if (document.querySelectorAll('.chkJob:checked').length == 0) {
			countErrors++;
		}

		//None error
		if (countErrors == 0) {
			//Verify cookie
			var idUser = getUrlVars()["id"];
			//Get values from user registration page
			var firstName = document.getElementById("firstNameRegistration").value;
			var lastName = document.getElementById("lastNameRegistration").value;
			var email = document.getElementById("emailRegistration").value;

			var arrayJobs = "";
			var arrayJobsValue = [];
			arrayJobs = document.querySelectorAll('.chkJob:checked');

			for (var i = 0; i < arrayJobs.length; i++) {
				arrayJobsValue[i] = arrayJobs[i].value;
			};

			var username = document.getElementById("usernameRegistration").value;
			var password = document.getElementById("passwordRegistration").value;
			var secretQuestion = document.getElementById("secretQuestionRegistration").value;
			var secretAnswer = document.getElementById("secretAnswerRegistration").value;

			//Use ajax to create new user
			$.ajax({
				type : 'POST',
				url : '_control/users-registration-c.php',
				dataType : "json",
				data : {
					insertUser : true,
					firstName : firstName,
					lastName : lastName,
					email : email,
					arrayJobs : arrayJobsValue,
					username : username,
					password : password,
					secretQuestion : secretQuestion,
					secretAnswer : secretAnswer,
					idUser : idUser
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Page is loading...", "info");
				}
			}).done(function(data) {
				if (data[0].error == undefined) {
					showAlertMessage("User save with success.", "success");
					saveImages("profile", data[1].idUser);
					window.location.href = "users-registration.php";
				} else {
					showAlertMessage(data[0].error, "danger");
				}
			});
		}
		//Returned errors
		else {
			showAlertMessage("Incorrect fields, please fill correctly.", "danger");
		}

	});
});
