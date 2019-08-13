<?php

if (isset($_POST["rollbackDeletComment"])) {
	include_once ("../_model/tests-m.php");

	$mReturn = rollbackDeletComment($_POST["idComment"]);

	$statusArray = array();

	//Not found datas
	if (!$mReturn) {
		array_push($statusArray, array("erro" => "Server is bussy"));
	} else {
		array_push($statusArray, array("return" => $mReturn));
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["deletComment"])) {
	include_once ("../_model/tests-m.php");

	$mReturn = deletComment($_POST["idComment"]);

	$statusArray = array();

	//Not found datas
	if (!$mReturn) {
		array_push($statusArray, array("erro" => "Server is bussy"));
	} else {
		array_push($statusArray, array("return" => $mReturn));
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["postComment"])) {
	include_once ("../_model/tests-m.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/TestsCommentsMc.php");

	$statusArray = array();

	//need a comment to post
	if (!(isset($_POST["comment"])) || trim($_POST["comment"]) == "") {
		array_push($statusArray, array("erro" => "Please, insert a comment"));
	} else {
		$mReturn = postComment($_POST["idTest"], unserialize($_SESSION["User"]) -> idUser, $_POST["comment"]);

		//Not found datas
		if (!$mReturn) {
			array_push($statusArray, array("erro" => "Server is bussy"));
		} else {
			array_push($statusArray, array("return" => $mReturn));

			//Send e-mail with messages
			$allComments = getComments($_POST["idTest"]);

			$message = "";

			$idUsers = "";

			for ($count = 0; $count < count($allComments); $count++) {
				$message .= $allComments[$count] -> dateComment . "::";
				$message .= "[" . $allComments[$count] -> completeName . "]" . "::";
				$message .= nl2br($allComments[$count] -> commentText);
				$message .= "<br/>";

				$idUsers .= $allComments[$count] -> idUser . ",";
			}

			$idUsers .=  unserialize($_SESSION["User"]) -> idUser;

			$allEmails = getUsersEmail($idUsers);

			PHPMailerMc::sendEmail($allEmails, $message, "Test Comment", "#" . $_POST["idTest"]);
		}
	}

	echo json_encode($statusArray);
	return;
}

if (isset($_POST["getComments"])) {
	include_once ("../_model/TestsCommentsMc.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/tests-m.php");

	$commentsArray = getComments($_POST["idTest"]);

	//Not found datas
	if (count($commentsArray) == 0) {
		array_push($commentsArray, array("erro" => "No comments registred"));
	} else {
		//Get info from active user
		array_push($commentsArray, array(unserialize($_SESSION["User"])));
	}

	echo json_encode($commentsArray);

	return;
}

if (isset($_POST["setStatus"])) {
	include_once ("../_model/tests-m.php");
	include_once ("../_model/UserMc.php");

	$statusArray = setStatus($_POST["status"], $_POST["idTest"], (unserialize($_SESSION["User"]) -> idUser));

	//Not found datas
	if (count($statusArray) == 0) {
		array_push($statusArray, array("erro" => "Error X_x"));
	} else {
		//Finded :-)
	}

	echo json_encode($statusArray);

	return;
}
if (isset($_POST["getStatus"])) {
	include_once ("../_model/tests-m.php");

	$statusArray = getStatus();

	//Not found datas
	if (count($statusArray) == 0) {
		array_push($statusArray, array("erro" => "No Test Status registred"));
	} else {
		//Finded :-)
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["getTests"])) {
	include_once ("../_model/TestsMc.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/tests-m.php");

	$mStatus = null;
	$mNeedAnswering = null;

	if (isset($_POST["status"]))
		$mStatus = ($_POST["status"]);

	if (isset($_POST["needAnswering"]))
		if ($_POST["needAnswering"] != "false")
			$mNeedAnswering = (unserialize($_SESSION["User"]) -> idUser);

	$testsArray = getTests($mStatus, $mNeedAnswering);

	//Not found datas
	if (count($testsArray) == 0) {
		array_push($testsArray, array("erro" => "No tests finded"));
	} else {
		//Finded :-)

		//change de null date to 00:00:00
		for ($count = 0; $count < sizeof($testsArray); $count++) {
			if ($testsArray[$count] -> testDateReady == null)
				$testsArray[$count] -> testDateReady = "__/__/____";
		}

		//Get info from active user
		array_push($testsArray, array(unserialize($_SESSION["User"])));
	}

	echo json_encode($testsArray);

	return;
}
?>