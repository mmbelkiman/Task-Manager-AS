<?php
/**
 * /_public-html/_model/users-registration-m.php
 *
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 23:23
 * Campinas-SP | Brazil
 */

if (!isset($_SESSION))
	session_start();
include_once $_SESSION["PATH_SERVER"] . "/_commons/includes-inside.php";
include_once $_SESSION["PATH_SERVER"] . "/_model/JobMc.php";

/*
 * Search for all jobs on database
 */
function getJobs() {

	//Create queries
	$queryJobs = "SELECT idJob, job FROM " . ValidateQueriesMc::$dbName . ".tbJobs";

	//Get user jobs
	$rowsJobs = ValidateQueriesMc::StartQuery($queryJobs);

	//Not found datas
	if ($rowsJobs === false) {
		return $rowsJobs;
	}

	$jobsArray = array();

	//Populate array with jobs
	foreach ($rowsJobs as $row) {
		$job = new JobMc("", $row["idJob"], $row["job"]);
		array_push($jobsArray, $job);
	}

	return $jobsArray;
}

function getUserById($idUser) {
	include_once "../_model/UserMc.php";

	//Create queries
	$queryUser = " SELECT idUser, name,lastName,email,photo,username,secretQuestion  ";
	$queryUser .= " FROM " . ValidateQueriesMc::$dbName . ".tbUsers Where idUser = ? ";

	$queryJob = " SELECT uj.idJob FROM " . ValidateQueriesMc::$dbName . ".tbUsers u ";
	$queryJob .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs uj ON uj.idUser = u.idUser ";
	$queryJob .= "WHERE u.idUser = ? ";

	//Get user
	$resultQuery = ValidateQueriesMc::StartQuery($queryUser, array($idUser));

	//Get job
	$resultQueryJob = ValidateQueriesMc::StartQuery($queryJob, array($idUser));

	//Not found datas
	if ($resultQuery === false) {
		return $resultQuery;
	}

	$userArray = array();
	$userArrayJob = array();
	//Populate array with jobs
	foreach ($resultQueryJob as $row) {
		array_push($userArrayJob, $row);
	}

	//Populate array with jobs
	foreach ($resultQuery as $row) {
		$user = new UserMc($row["idUser"], $userArrayJob, $row["name"], $row["lastName"], $row["email"], $row["photo"], $row["username"], $row["secretQuestion"], "", "");
		array_push($userArray, $user);
	}

	return $userArray;
}

/*
 * Insert users on database
 */
function insertUser($firstName, $lastName, $email, $arrayJobs, $username, $password, $secretQuestion, $secretAnswer) {

	//Declare variables
	$empty = "";
	$activeUserTrue = "1";

	//Insert users
	$queryInsertUser = "INSERT INTO " . ValidateQueriesMc::$dbName . ".tbUsers (name,lastName,email,username,password,secretQuestion,secretAnswer,activeUser) ";
	$queryInsertUser .= "VALUES(?,?,?,?,?,?,?,?)";
	$resultQueryInsertUser = ValidateQueriesMc::StartQuery($queryInsertUser, array("$firstName", "$lastName", "$email", "$username", "$password", "$secretQuestion", "$secretAnswer", "$activeUserTrue"));
	if ($resultQueryInsertUser === false) {
		return false;
	}

	//Insert reminders
	$queryInsertbReminders = "INSERT INTO " . ValidateQueriesMc::$dbName . ".tbReminders VALUES (?,?)";
	$resultQueryInsertbReminders = ValidateQueriesMc::StartQuery($queryInsertbReminders, array("$resultQueryInsertUser", "$empty"));
	if ($resultQueryInsertbReminders === false) {
		return false;
	}

	//Insert jobs
	for ($count = 0; $count < sizeof($arrayJobs); $count++) {
		$queryJobs = "INSERT INTO " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs VALUES (?,?)";
		$resultQueryJobs = ValidateQueriesMc::StartQuery($queryJobs, array("$arrayJobs[$count]", "$resultQueryInsertUser"));
		if ($resultQueryJobs === false) {
			return false;
		}
	}

	return $resultQueryInsertUser;
}

/*
 * Update photo user on database
 */
function updatePhotoProfile($filename, $idUser) {
	//Update users
	$queryUpdateUser = " UPDATE " . ValidateQueriesMc::$dbName . ".tbUsers ";
	$queryUpdateUser .= " SET photo = ? ";
	$queryUpdateUser .= " WHERE idUser = ? ";
	$resultQueryUpdateUser = ValidateQueriesMc::StartQuery($queryUpdateUser, array("$filename", "$idUser"));
	if ($resultQueryUpdateUser === false) {
		return false;
	}

	return true;
}

/*
 * Update users on database
 */
function updateUser($firstName, $lastName, $email, $arrayJobs, $username, $password, $secretQuestion, $secretAnswer, $idUser) {
	//Update users
	$queryUpdateUser = "UPDATE " . ValidateQueriesMc::$dbName . ".tbUsers ";
	$queryUpdateUser .= "SET name = ?,lastName = ?,email = ?,username = ?,password = ?,secretQuestion = ?,secretAnswer = ?,activeUser = ? ";
	$queryUpdateUser .= "WHERE idUser = ? ";
	$resultQueryUpdateUser = ValidateQueriesMc::StartQuery($queryUpdateUser, array("$firstName", "$lastName", "$email", "$username", "$password", "$secretQuestion", "$secretAnswer", "1", "$idUser"));
	if ($resultQueryUpdateUser === false) {
		return false;
	}

	//Update jobs
	$queryDeleteJobs = "DELETE FROM " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs WHERE idUser = ?";
	$resultQueryDeleteJobs = ValidateQueriesMc::StartQuery($queryDeleteJobs, array("$idUser"));
	if ($resultQueryDeleteJobs === false) {
		return false;
	}

	for ($count = 0; $count < sizeof($arrayJobs); $count++) {
		$queryJobs = "INSERT INTO " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs VALUES (?,?)";
		$resultQueryJobs = ValidateQueriesMc::StartQuery($queryJobs, array("$arrayJobs[$count]", "$idUser"));
		if ($resultQueryJobs === false) {
			return false;
		}
	}

	return true;
}

//Get username in table tbUsers
function getUsernameFromUsersByUsername($username) {
	$queryUsername = "SELECT username FROM " . ValidateQueriesMc::$dbName . ".tbUsers WHERE username = ?";
	$resultUsername = ValidateQueriesMc::StartQuery($queryUsername, array("$username"));
	if ($resultUsername === false) {
		return false;
	}
	return true;
}

//Get email in table tbUsers
function getEmailFromUsersByEmail($email) {
	$queryEmail = "SELECT email FROM " . ValidateQueriesMc::$dbName . ".tbUsers WHERE email = ?";
	$resultQueryEmail = ValidateQueriesMc::StartQuery($queryEmail, array("$email"));
	if ($resultQueryEmail === false) {
		return false;
	}
	return true;
}
?>
