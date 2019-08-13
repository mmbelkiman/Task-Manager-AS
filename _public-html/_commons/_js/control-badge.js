function refreshBadges() {
	$(".showBadge").on("mousemove", function(event) {

		//Create badge
		var existBadge = document.getElementById("badgeAlundra");
		if (existBadge == null && existBadge != "") {
			var html = "";
			var idUser = $(this).attr("data-id");
			var srcPhoto = "_images/no-photo.jpg";

			if (idUser != undefined) {
				srcPhoto = "_images/_" + idUser + "/profile.jpg";

				//This image is valid?
				$.ajax({
					url : srcPhoto,
					type : 'HEAD',
					async : false,
					error : function() {
						srcPhoto = "_images/no-photo.jpg";
					}
				});
			}

			html += "<div id='badgeAlundra' style='top:" + event.pageY + "px;left:" + event.pageX + "px;'>";
			html += "	<div style='float:left'>";
			html += "		<img class='badgePhoto' src='" + srcPhoto + "'>";
			html += "	</div>";

			html += "	<div style='float:left'>";
			html += "		<div style='float:left'>";
			html += "			<img class='badgeLogo' src='_images/logotype_as.png'>";
			html += "		</div>";
			html += " 		Name: </br>";
			html += " 		Username: </br>";
			html += " 		E-mail: </br>";
			html += " 		Job: </br>";
			html += "	</div>";

			html += "</div>";

			$("body").prepend(html);
		} else {
			$("#badgeAlundra").css("top", event.pageY);
			$("#badgeAlundra").css("left", event.pageX + 10);
		}
	});

	$(".showBadge").on("mouseout", function() {
		//Destroy badge
		var existBadge = document.getElementById("badgeAlundra");
		if (existBadge != null) {
			$("#badgeAlundra").remove();
		}
	});

}
