/**
 * Control page-cron functions
 * @date 2014-06-17
 * @author Marcelo Belkiman(marcelobelkiman[at]gmail[dot]com), Elton Martins(eltonmartinsmarcelino[at]gmail[dot]com)
 *
 */
$(document).ready(function() {
	var hours;
	var minutes;
	var seconds;
	var timeToSave = 0;
	var mSetWindowAttention = setWindowAttention;
	var windowFocus = false;

	//Populate cron with correct data
	$.ajax({
		type : 'POST',
		url : '_control/page-cron-c.php',
		dataType : "json",
		data : {
			getCron : true
		},
		async : false,
		beforeSend : function() {

		}
	}).done(function(data) {
		if (data[0].erro == undefined) {
			$("#cronTime").html("<span class='glyphicon glyphicon-dashboard' title='Running Time'></span> " + data[0].time);
			$("#cronInfo").html("#" + data[0].idTask + " - " + data[0].nameTask + "&nbsp");

			if (getCookie("hours") == "") {
				hours = (data[0].time).substring(0, 2);
				minutes = (data[0].time).substring(3, 5);
				seconds = (data[0].time).substring(6, 8);
			} else {
				hours = getCookie("hours");
				minutes = getCookie("minutes");
				seconds = getCookie("seconds");
			}
		} else {
			showAlertMessage(data[0].erro, "danger");
		}
	});

	//verifiy window focus
	$(window).focus(function() {
		windowFocus = true;
	});
	$(window).blur(function() {
		windowFocus = false;
	});

	//Create screen relax
	function createScreenRelax() {
		saveCron();
		var html = "";

		html += "<div class='modal fade' id='modaRelax' tabindex='-1' role='dialog' data-keyboard='false' data-backdrop='static' aria-hidden='true'> ";
		html += "	<div class='modal-dialog'> ";
		html += "		<div class='modal-content'> ";
		html += "			<div class='modal-header'> ";
		html += "				<button class='close btn-closeRelax' type='button' data-dismiss='modal' aria-hidden='true'> ";
		html += "					&times; ";
		html += "				</button> ";
		html += "				<h4 class='modal-title'>Time to relax =) </h4> ";
		html += "			</div> ";
		html += "			<div class='modal-body'> ";
		html += "				<h4>Hey! you worked hard in the latest 50 minutes.</h4> </br> ";
		html += "				We recommend making a 10 minute break to recharge your creativity. </br></br> ";
		html += "				<b>Do not know what to do?</b> </br> ";
		html += "				You can for example go hiking, go get a coffee or water, or simple making nothing. </br> ";
		html += "				Here a current news for you, if you want to read: </br> ";
		html += "				<div id='rssToRelax1'>  </div>";
		html += "				<div id='rssToRelax2'>  </div>";
		html += "				<div id='rssToRelax3'>  </div>";
		html += "				</br> ";
		html += "				<div style='text-align:center; font-size:2.0em'> ";
		html += "					<span>00</span>:";
		html += "					<span id='minutesRelax'>10</span>:";
		html += "					<span id='secondsRelax'>00</span>";
		html += "				</div> ";
		html += "			</div> ";
		html += "			<div class='modal-footer'> ";
		html += "				<button class='btn btn-default btn-closeRelax' type='button'data-dismiss='modal'> ";
		html += "					Go to Work! ";
		html += "				</button> ";
		html += "			</div> ";
		html += "		</div> ";
		html += "	</div> ";
		html += "</div> ";

		$("body").prepend(html);

		$('#rssToRelax1').rssfeed('http://tecnologia.uol.com.br/ultnot/index.xml', {
			limit : 2,
			date : false,
			titletag : "h5",
			header : false,
			linktarget : "_blank"
		});

		$('#rssToRelax2').rssfeed('http://feeds.feedburner.com/PapodehomemLifestyleMagazine', {
			limit : 2,
			date : false,
			titletag : "h5",
			header : false,
			linktarget : "_blank",
			content : false
		});

		$('#rssToRelax3').rssfeed('http://tvcultura.cmais.com.br/jornaldacultura/feed', {
			limit : 2,
			date : false,
			titletag : "h5",
			header : false,
			linktarget : "_blank",
			content : false
		});

		$(".btn-closeRelax").on("click", function() {
			$("#minutesRelax").html("10");
			$("#secondsRelax").html("00");
			changeClock();
			modalRelaxTimer = null;
		});

		function modalRelaxTimer() {
			setTimeout(function() {
				var minutes = parseInt($("#minutesRelax").html().trim());
				var seconds = parseInt($("#secondsRelax").html().trim());

				if (seconds > 0) {
					seconds--;
				} else {
					seconds = 59;
					//Control minutes
					if (minutes > 0)
						minutes--;
				}

				if (seconds.toString().length < 2)
					seconds = "0" + seconds.toString();
				if (minutes.toString().length < 2)
					minutes = "0" + minutes.toString();

				$("#secondsRelax").html(seconds);
				$("#minutesRelax").html(minutes);

				if ((parseInt(minutes) > 0) || (parseInt(seconds) > 0))
					modalRelaxTimer();
			}, 1000);
		}

		modalRelaxTimer();
		$('#modaRelax').modal('show');
	}

	//Move clock
	function changeClock() {

		//Save after 5 minutes passed
		if (timeToSave >= (5 * 59)) {
			saveCron();
			timeToSave = 0;
		}
		timeToSave += 1;

		//Control time to relax
		var timeToRelax = getCookie("timeToRelax");
		if (timeToRelax == undefined || timeToRelax.trim() == "")
			timeToRelax = 1;
		else
			timeToRelax = parseInt(timeToRelax) + 1;

		// 50minutes work // 10 minutes to relax
		if (timeToRelax == (50 * 60))
			mSetWindowAttention.start("NEW MESSAGE");

		if (timeToRelax >= (50 * 60)) {
			if (windowFocus) {
				mSetWindowAttention.stop();
				createScreenRelax();
				timeToRelax = 0;
			}
		}

		setCookie("timeToRelax", timeToRelax, 365);

		//Control Seconds
		if (seconds < 59)
			seconds++;
		else {
			seconds = 0;
			//Control minutes
			if (minutes < 59)
				minutes++;
			else {
				minutes = 0;
				//Control Hours
				hours++;
			}
		}
		if (seconds.toString().length < 2)
			seconds = "0" + seconds.toString();
		if (minutes.toString().length < 2)
			minutes = "0" + minutes.toString();
		if (hours.toString().length < 2)
			hours = "0" + hours.toString();

		setCookie("seconds", seconds, 365);
		setCookie("minutes", minutes, 365);
		setCookie("hours", hours, 365);

		$("#cronTime").html("<span class='glyphicon glyphicon-dashboard' title='Running Time'></span> " + hours + ":" + minutes + ":" + seconds);

		setTimeout(function() {
			if (timeToRelax != 0)
				changeClock();
		}, 1000);
	}

	//Call one time, after that, he is auto-called after one in one seconds(setTimeOut)
	changeClock();

	// Click Listeners
	$("#cronStop").click(function() {
		$.ajax({
			type : 'POST',
			url : '_control/page-cron-c.php',
			dataType : "json",
			data : {
				enableDisableCron : true,
			},
			async : false,
			beforeSend : function() {
				showAlertMessage("Saving cron...", "warning");
			}
		}).done(function(data) {
			if (data[0].erro == undefined) {
				removeCookie("seconds");
				removeCookie("minutes");
				removeCookie("hours");
				location.reload();
			} else {
				showAlertMessage(data[0].erro, "danger");
			}
		});
	});

	function saveCron() {
		$.ajax({
			type : 'POST',
			url : '_control/page-cron-c.php',
			dataType : "json",
			dataType : "json",
			data : {
				saveCron : true,
			},
			async : false,
			beforeSend : function() {

			}
		}).done(function(data) {
			if (data[0].erro == undefined) {

			} else {
				showAlertMessage(data[0].erro, "danger");
			}
		});
	}

	//User logout ou exit the current page, try save your remindes
	$("#logout").click(function() {
		saveCron();
	});
});
