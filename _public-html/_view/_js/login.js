/**
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 *
 */
$(document).ready(function() {

	if (getCookie("loginUser") != "" && getCookie("passwordUser") != "") {
		var loginUser = getCookie("loginUser");
		var passwordUser = getCookie("passwordUser");
		$.ajax({
			type : 'POST',
			url : '_control/login-c.php',
			dataType : "json",
			data : {
				getLoginSuccess : true,
				cookies : true,
				user : loginUser,
				password : passwordUser
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				window.location.href = "index.php";
			} else {
				showAlertMessage(data[0].error, "danger");
			}
		});
	}

	$.ajax({
		type : "POST",
		url : "_control/login-c.php",
		dataType : "json",
		data : {
			getOnlySessionCountErrors : true
		},
		async : false
	}).done(function(data) {
		if (data[0].countError == undefined) {

		} else {
			if (data[0].countError >= 5) {
				showRecaptcha("captcha-wrap");
			}
		}
	});

	//Try to login when you press the enter key
	$('#loginUser, #passwordUser').keyup(function(e) {
		if (e.keyCode == 13)
			startLogin();
	});
	
	//Try to login when you click in submit button
	$("#btnLoginSubmit").click(function() {
		startLogin();
	});
	
	function startLogin() {
		//Get login and password variable from _view/_pages/login.php
		var loginUser = $("#loginUser").val();
		var passwordUser = $("#passwordUser").val();
		var rememberMe = $("#chkRememberMe:checked").val() ? true : false;

		if ($("#captcha-wrap").hasClass("recaptcha_isnot_showing_audio") && $("#captcha-wrap").hasClass("recaptcha_nothad_incorrect_sol")) {
			//Validate captcha
			if (validateCaptcha() == true) {
				//Execute validate login in ajax
				$.ajax({
					type : 'POST',
					url : '_control/login-c.php',
					dataType : "json",
					data : {
						getLoginSuccess : true,
						user : loginUser,
						password : passwordUser
					},
					async : false,
					beforeSend : function() {
						showAlertMessage("Looking your credentials", "warning");
					}
				}).done(function(data) {
					if (data[0].error == undefined) {
						//Create a cookies for if mark "Remember me"
						if (rememberMe == true) {
							addLoginCookies(loginUser, data[1].passwordUser);
							window.location.href = "index.php";
						} else {
							window.location.href = "index.php";
						}

					} else {
						showAlertMessage(data[0].error, "danger");
						if (data[1].countError >= 5) {
							showRecaptcha("captcha-wrap");
						}
					}
				});
			}
			//No validate captcha
		} else {
			//Execute validate login in ajax
			$.ajax({
				type : 'POST',
				url : '_control/login-c.php',
				dataType : "json",
				data : {
					getLoginSuccess : true,
					user : loginUser,
					password : passwordUser
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Looking your credentials", "warning");
				}
			}).done(function(data) {
				if (data[0].error == undefined) {
					if (rememberMe == true) {
						addLoginCookies(loginUser, data[1].passwordUser);
						window.location.href = "index.php";
					} else {
						window.location.href = "index.php";
					}
				} else {
					showAlertMessage(data[0].error, "danger");
					if (data[1].countError >= 5) {
						showRecaptcha("captcha-wrap");
					}
				}
			});
		}
	}

	//Validate captcha when exist
	function validateCaptcha() {
		var returnCaptcha = false;
		var challengeVal = $("#recaptcha_challenge_field").val();
		var responseVal = $("#recaptcha_response_field").val();

		var html = $.ajax({
			type : 'POST',
			url : '_commons/_php/start-captcha.php',
			dataType : "json",
			data : {
				validateCaptcha : true,
				recaptcha_challenge_field : challengeVal,
				recaptcha_response_field : responseVal
			},
			async : false
		}).done(function(data) {
			if (data[0].error == undefined) {
				returnCaptcha = true;
			} else {
				document.getElementById("loginUser").value = "";
				document.getElementById("passwordUser").value = "";
				showAlertMessage("Your captcha is incorrect. Please try again", "danger");
				Recaptcha.reload();
				returnCaptcha = false;
			}
		});
		return returnCaptcha;
	}

	//Show the recaptcha in html page
	function showRecaptcha(element) {
		//local public key: 6LduA_YSAAAAAHxKLuHMKx7ZelfvyQClzvavsj2B

		//Using the public key of site
		Recaptcha.create("6LdddPYSAAAAABXYEfL6a5-VGh1T1M4ewSNBwKa1", element, {
			theme : "red",
			callback : Recaptcha.focus_response_field
		});
	}

	//Stored user and password in a cookies
	function addLoginCookies(loginUser, passwordUser) {
		setCookie("loginUser", loginUser, 365);
		setCookie("passwordUser", passwordUser, 365);
	}

});
