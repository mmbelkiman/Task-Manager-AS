<?php
/**
 * /_public-html/_control/home-c.php
 *
 *
 *
 *
 * @package    _control
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.2
 * @date       2014/07/10 : 20:19
 * Campinas-SP | Brazil
 */
if (isset($_POST["setReminders"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Save reminds on the table
	$returnQuery = setReminds( unserialize($_SESSION["User"]) -> idUser, $_POST["reminders"]);
	$return = array();
	//Not found datas
	if ($returnQuery == false) {
		array_push($return, array("error" => "Error to update reminds"));
	} else {
		//Finded :-)
		array_push($return, array("validate" => "true"));
	}

	echo json_encode($return);
	return;
}

if (isset($_POST["getReminders"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Save reminds on the table
	$reminds = getReminds(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($reminds) == "") {
		array_push($reminds, array("error" => "No reminds found"));
	} else {
		//Finded :-)
	}

	echo json_encode($reminds);
	return;
}

if (isset($_POST["getCountTasksAnalyse"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Get count tasks analyse on the table
	$countTasks = getCountTasksAnalyse(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($countTasks) == "") {
		array_push($countTasks, array("error" => "No analyse tasks found on count"));
	} else {
		//Finded :-)
	}
	
	echo json_encode($countTasks);
	
	return;
}

if (isset($_POST["getCountTasksDevelop"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Get count tasks develop on the table
	$countTasks = getCountTasksDevelop(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($countTasks) == "") {
		array_push($countTasks, array("error" => "No develop tasks found on count"));
	} else {
		//Finded :-)
	}

	echo json_encode($countTasks);
	return;
}

if (isset($_POST["getCountTasksOpen"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Get count tasks open on the table
	$countTasks = getCountTasksOpen(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($countTasks) == "") {
		array_push($countTasks, array("error" => "No open tasks found on count"));
	} else {
		//Finded :-)
	}

	echo json_encode($countTasks);
	return;
}

if (isset($_POST["getTasksDisapproval"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Get tasks disapproved on the table
	$tasksDisapproval = getTasksDisapproval(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($tasksDisapproval) == "") {
		array_push($tasksDisapproval, array("error" => "No tasks disapproval"));
	} else {
		//Finded :-)
	}

	echo json_encode($tasksDisapproval);
	return;
}

if (isset($_POST["getTasksApproval"])) {
	//Includes
	include_once ("../_model/home-m.php");
	include_once ("../_model/UserMc.php");

	//Get tasks approved on the table
	$tasksApproval = getTasksApproval(unserialize($_SESSION["User"]) -> idUser);

	//Not found datas
	if (count($tasksApproval) == "") {
		array_push($tasksApproval, array("error" => "No tasks approval"));
	} else {
		//Finded :-)
	}

	echo json_encode($tasksApproval);
	return;
}

if (isset($_POST["getCommits"])) {
	include_once ("../_model/CommitMc.php");
	include_once ("../_model/home-m.php");

	$commitsArray = getCommitsNotFinalized(unserialize($_SESSION["User"]) -> idUser, $_POST["reminders"]);

	//Not found datas
	if (count($commitsArray) == 0) {
		array_push($commitsArray, array("error" => "No commits registred"));
	} else {
		//Finded :-)
	}
	echo json_encode($commitsArray);

	return;
}

if (isset($_POST["uploadCommits"])) {
	include_once ("../_model/home-m.php");

	$commitsArray = updateDateUploadByCommit($_POST["idCommit"]);

	$return = array();

	//Not found datas
	if (count($commitsArray) == 0) {
		array_push($return, array("error" => "Error to upload task committed"));
	} else {
		//Finded :-)
		array_push($return, array("validate" => "true"));
	}
	echo json_encode($return);

	return;
}

if (isset($_POST["insertCommits"])) {
	include_once ("../_model/home-m.php");

	$returnInsert = insertCommits($_POST["idTask"], $_POST["files"]);
	$returnUpdate = updateCommits($_POST["idTask"]);

	$return = array();

	if ($returnInsert === false || $returnUpdate === false) {
		array_push($return, array("error" => "Fail to register a new user =("));
	} else {
		//Finded :-)
		array_push($return, array("validate" => "true"));
	}
	echo json_encode($return);

	return;
}
?>