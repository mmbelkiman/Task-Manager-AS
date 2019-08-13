/**
 * Control page-menu functions
 * @date 2014-06-10
 * @author Marcelo Belkiman
 */

$(document).ready(function() {
	/**
	 * Read the URL to know where page is active, after that change de active on the top menu (bootstrap)
	 */
	$(function ChangeActiveMenu() {
		var mActivePage = (window.location.pathname).substr(28, (window.location.pathname.length) - 28);
		mActivePage = mActivePage.substr(0, mActivePage.length - 4);

		if (mActivePage == "home") {
			$('.active').removeClass('active');
			$("#home").addClass('active');
		}

		if (mActivePage == "users-show" || mActivePage == "users-registration") {
			$('.active').removeClass('active');
			$("#users").addClass('active');
		}

		if (mActivePage == "tasks-show" || mActivePage == "tasks-registration") {
			$('.active').removeClass('active');
			$("#tasks").addClass('active');
		}
		if (mActivePage == "tests") {
			$('.active').removeClass('active');
			$("#tests").addClass('active');
		}
	});

	//Get user name and put on the page-menu
	$.ajax({
		type : 'POST',
		url : '_control/page-menu-c.php',
		dataType : "json",
		data : {
			getUserName : true
		},
		async : false,
		beforeSend : function() {

		}
	}).done(function(data) {
		if (data[0].erro == undefined) {
			$("#userLogged").html("USER LOGGED: " + data);
		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	//LogOut Function
	$("#logout").click(function() {
		$.when($.ajax()).done(function() {
			$.ajax({
				type : 'POST',
				url : '_control/page-menu-c.php',
				dataType : "json",
				data : {
					logOut : true
				},
				async : false,
				beforeSend : function() {
					showAlertMessage("Logging out...Bye Bye o/", "info");
				}
			}).done(function(data) {
				if (data[0].erro == undefined) {
					removeCookie("loginUser");
					removeCookie("passwordUser");
					window.location.href = "index.php";
				} else {
					showAlertMessage(data[0].erro, "danger");
				}
			});
		});
	});
});