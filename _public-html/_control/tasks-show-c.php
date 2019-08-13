<?php

if (isset($_POST["saveTesters"])) {
	include_once ("../_model/tasks-show-m.php");
	include_once ("../_model/UserMc.php");

	$mTask = getTestWithTask($_POST["idTask"]);

	$testers = "";
	$idUsers = "";

	for ($count = 0; $count < count($_POST); $count++) {
		if (isset($_POST["idUser" . $count])) {

			if ($_POST["idUser" . $count] != -1) {
				$testers .= "(" . $mTask[0] . "," . $_POST["idUser" . $count] . ")";
				$testers .= ",";
				$idUsers .= $_POST["idUser" . $count] . ",";
			}
		}
	}
	$testers = substr($testers, 0, -1);
	$idUsers = substr($idUsers, 0, -1);

	$mReturn = saveTesters($testers);

	$statusArray = array();

	//Not found datas
	if (!$mReturn) {
		array_push($statusArray, array("erro" => "Server is bussy"));
	} else {
		array_push($statusArray, array("return" => $mReturn));

		//Send E-mail
		$fullName =      unserialize($_SESSION["User"]) -> name . " " .    unserialize($_SESSION["User"]) -> lastName;
		$message = "User " . $fullName . " put you to make test in task #" . $_POST["idTask"];

		$idUsers .=    unserialize($_SESSION["User"]) -> idUser;

		$allEmails = getUsersEmail($idUsers);

		PHPMailerMc::sendEmail($allEmails, $message, "Test Request", "#" . $_POST["idTask"]);
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["returnDevelopment"])) {
	include_once ("../_model/tasks-show-m.php");

	$mReturn = returnDevelopment($_POST["idTask"]);

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

if (isset($_POST["nextPhase"])) {
	include_once ("../_model/tasks-show-m.php");

	$mReturn = nextPhase($_POST["idTask"]);

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
	include_once ("../_model/tasks-show-m.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/TasksCommentsMc.php");

	$statusArray = array();

	//need a comment to post
	if (!(isset($_POST["comment"])) || trim($_POST["comment"]) == "") {
		array_push($statusArray, array("erro" => "Please, insert a comment"));
	} else {
		$mReturn = postComment($_POST["idTask"],   unserialize($_SESSION["User"]) -> idUser, $_POST["comment"]);

		//Not found datas
		if (!$mReturn) {
			array_push($statusArray, array("erro" => "Server is bussy"));
		} else {
			array_push($statusArray, array("return" => $mReturn));

			//Send e-mail with messages
			$allComments = getComments($_POST["idTask"]);

			$message = "";
			$idUsers = "";

			for ($count = 0; $count < count($allComments); $count++) {
				$message .= $allComments[$count] -> dateComment . "::";
				$message .= "[" . $allComments[$count] -> completeName . "]" . "::";
				$message .= nl2br($allComments[$count] -> commentText);
				$message .= "<br/>";

				$idUsers .= $allComments[$count] -> idUser . ",";
			}

			if (isset($_POST["idUserDevelop"]))
				if ($_POST["idUserDevelop"] != null)
					$idUsers .= $_POST["idUserDevelop"] . ",";

			$idUsers .=   unserialize($_SESSION["User"]) -> idUser;

			$allEmails = getUsersEmail($idUsers);

			PHPMailerMc::sendEmail($allEmails, $message, "Task Comment", "#" . $_POST["idTask"]);
		}
	}

	echo json_encode($statusArray);
	return;
}

if (isset($_POST["getComments"])) {
	include_once ("../_model/TasksCommentsMc.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/tasks-show-m.php");

	$usersArray = getComments($_POST["idTask"]);

	//Not found datas
	if (count($usersArray) == 0) {
		array_push($usersArray, array("erro" => "No comments registred"));
	} else {
		//Get info from active user
		array_push($usersArray, array(unserialize($_SESSION["User"])));
	}

	echo json_encode($usersArray);

	return;
}

if (isset($_POST["getAllUsers"])) {
	include_once ("../_model/UserMc.php");
	include_once ("../_model/users-show-m.php");

	if (isset($_POST["idUser"]))
		$usersArray = getUsers($_POST["idUser"]);
	else
		$usersArray = getUsers();
	//Not found datas
	if (count($usersArray) == 0) {
		array_push($usersArray, array("erro" => "No users registred"));
	} else {
		//Finded :-)
	}

	echo json_encode($usersArray);

	return;
}

if (isset($_POST["getTestersAtTask"])) {
	include_once ("../_model/tasks-show-m.php");

	$usersArrayTest = getTestersAtTask($_POST["idTask"]);

	//Not found datas
	if (count($usersArrayTest) == 0) {
		array_push($usersArrayTest, array("erro" => "No comments registred"));
	} else {
		//I find =]
	}

	echo json_encode($usersArrayTest);

	return;
}

if (isset($_POST["getAllUsersTesters"])) {
	include_once ("../_model/UserMc.php");
	include_once ("../_model/JobMc.php");
	include_once ("../_model/users-show-m.php");

	$usersArray = getUsers();
	//Not found datas
	if (count($usersArray) == 0) {
		array_push($usersArray, array("erro" => "No users registred"));
	} else {
		//save only testers
		$usersTesterArray = array();
		foreach ($usersArray as $user) {

			foreach ($user -> jobs as $userJob) {
				if ($userJob -> job == "Tester")
					array_push($usersTesterArray, $user);
			}
		}

		$usersArray = $usersTesterArray;
	}

	echo json_encode($usersArray);

	return;
}

if (isset($_POST["transferAnotherUser"])) {
	include_once ("../_model/UserMc.php");
	include_once ("../_model/tasks-show-m.php");

	$mReturn = transferAnotherUser($_POST["idTask"], $_POST["idUser"]);

	$statusArray = array();

	//Not found datas
	if (!$mReturn) {
		array_push($statusArray, array("erro" => "Server is bussy"));
	} else {
		array_push($statusArray, array("return" => $mReturn));

		//Send E-mail
		$fullName =      unserialize($_SESSION["User"]) -> name . " " .    unserialize($_SESSION["User"]) -> lastName;
		$message = "User " . $fullName . " as transfer to you task #" . $_POST["idTask"];

		$allEmails = getUsersEmail($_POST["idUser"]);

		PHPMailerMc::sendEmail($allEmails, $message, "Task Transfer", "#" . $_POST["idTask"]);
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["requestDeveloper"])) {
	include_once ("../_model/tasks-show-m.php");
	include_once ("../_model/UserMc.php");

	$mReturn = requestDeveloper($_POST["idTask"],unserialize($_SESSION["User"]) -> idUser);

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

if (isset($_POST["rollbackNextPhase"])) {
	include_once ("../_model/tasks-show-m.php");

	$mReturn = rollbackNextPhase($_POST["idTask"]);

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

if (isset($_POST["rollbackDeletTask"])) {
	include_once ("../_model/tasks-show-m.php");

	$mReturn = rollbackDeletTask($_POST["idTask"]);

	$statusArray = array();

	//Not found datas
	if (!$mReturn) {
		array_push($statusArray, array("erro" => "Server is bussy"));
	} else {
		array_push($statusArray, array("rollback" => $mReturn));
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["rollbackDeletComment"])) {
	include_once ("../_model/tasks-show-m.php");

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

if (isset($_POST["deletTask"])) {
	include_once ("../_model/tasks-show-m.php");

	$mReturn = deletTask($_POST["idTask"]);

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
	include_once ("../_model/tasks-show-m.php");

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

if (isset($_POST["getStatus"])) {
	include_once ("../_model/tasks-show-m.php");

	$statusArray = getStatus();

	//Not found datas
	if (count($statusArray) == 0) {
		array_push($statusArray, array("erro" => "No Task Status registred"));
	} else {
		//Finded :-)
	}

	echo json_encode($statusArray);

	return;
}

if (isset($_POST["getTasks"])) {
	include_once ("../_model/CronMc.php");
	include_once ("../_model/TaskMc.php");
	include_once ("../_model/UserMc.php");
	include_once ("../_model/tasks-show-m.php");

	$mStatus = null;
	$mOnlyMyTasks = null;
	$mOnlyMyTasksRequest = null;

	if (isset($_POST["status"]))
		$mStatus = ($_POST["status"]);

	if (isset($_POST["onlyMyTasks"]))
		if ($_POST["onlyMyTasks"] != "false")
			$mOnlyMyTasks = (unserialize($_SESSION["User"]) -> idUser);

	if (isset($_POST["onlyMyTasksRequest"]))
		if ($_POST["onlyMyTasksRequest"] != "false")
			$mOnlyMyTasksRequest = (unserialize($_SESSION["User"]) -> idUser);

	$tasksArray = getTasks($mStatus, $mOnlyMyTasks, $mOnlyMyTasksRequest);

	//Not found datas
	if (count($tasksArray) == 0) {
		array_push($tasksArray, array("erro" => "No tasks finded"));
	} else {
		//Finded :-)

		//change de null date to 00:00:00
		for ($count = 0; $count < sizeof($tasksArray); $count++) {
			if ($tasksArray[$count] -> updateDate == false)
				$tasksArray[$count] -> updateDate = "00:00:00";
			if ($tasksArray[$count] -> hoursReal == false)
				$tasksArray[$count] -> hoursReal = "00:00:00";
		}

		//Look if Cron is run
		$cronRun = false;
		$cronIdTask = -1;
		if (isset($_SESSION["Cron"])) {
			$cronRun = true;
			$cronIdTask = unserialize($_SESSION["Cron"]);
			$cronIdTask = $cronIdTask[0] -> idTask;
		}
		//Get info from active user
		array_push($tasksArray, array(unserialize($_SESSION["User"]), "cronRun" => $cronRun, "cronIdTask" => $cronIdTask));
	}

	echo json_encode($tasksArray);

	return;
}
?>