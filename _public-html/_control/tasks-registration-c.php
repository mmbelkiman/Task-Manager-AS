<?php
//Validate fields
if (isset($_POST["validateAllUserFields"])) {

	$statusArray = array();

	if (isset($_POST["selectField"])) {
		if ($_POST["selectField"] == "taskName") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a task name."));
			}
		}

		if ($_POST["selectField"] == "taskDatetime") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a date."));
			}
		}
	}

	if (count($statusArray) == "") {
		array_push($statusArray, array("validate" => "true"));
	}

	echo json_encode($statusArray);

	return;
}

//Get all jobs
if (isset($_POST["getJobs"])) {
	include_once ("../_model/tasks-registration-m.php");

	$jobsArray = getJobs();

	//Not found datas
	if (count($jobsArray) == "") {
		array_push($jobsArray, array("error" => "No users registered"));
	} else {
		//Finded :-)
	}

	echo json_encode($jobsArray);

	return;
}

//Insert a new user
if (isset($_POST["insertTask"])) {
	include_once "../_model/tasks-registration-m.php";
	include_once "../_model/UserMc.php";

	$tasksArray = array();
	$idUser = 0;
	if (isset($_SESSION["User"])) {
		$idUser =   unserialize($_SESSION["User"]) -> idUser;
	}

	$returnInsert = insertTasks($_POST["idJob"], $_POST["name"], $_POST["description"], $idUser, $_POST["datetime"]);
	if ($returnInsert === false) {
		array_push($tasksArray, array("error" => "Fail to register a new task =("));
	}

	if (count($tasksArray) == "") {
		array_push($tasksArray, array("validate" => "true"));
	}
	echo json_encode($tasksArray);

	return;
}
?>