<?php
if (file_exists("../_commons/includes-inside.php")) {
	include_once "../_commons/includes-inside.php";
} else {
	include_once "_commons/includes.php";
}

function getUserByUsernamePassword($username, $password) {
	//Declate queries
	$queryUser = "SELECT * FROM " .ValidateQueriesMc::$dbName .".tbUsers WHERE username = ? AND password = ?";
	$queryJobs = "SELECT j.idJob, j.job FROM " .ValidateQueriesMc::$dbName .".tbJobs j, " .ValidateQueriesMc::$dbName .".tbUsers u, " .ValidateQueriesMc::$dbName .".tbUsersXtbJobs uj ";
	$queryJobs .= "WHERE j.idJob = uj.idJob and u.idUser = uj.idUser and u.username = ?";

	$rowsUser = array();
	//Get user datas
	$rowsUser = ValidateQueriesMc::StartQuery($queryUser, array("$username", "$password"));

	$rowsJob = array();
	//Get jobs of user
	$rowsJob = ValidateQueriesMc::StartQuery($queryJobs, array("$username"));

	$usersArray = array();

	//Not found datas
	if ($rowsUser === false || $rowsJob === false) {
		return $usersArray;
	}
	
	$isAdministrator = false;
	if (in_array("Administrator", $rowsJob[0])) {
		$isAdministrator = true;
	}

	//Populate class UserMC
	foreach ($rowsUser as $row) {
		$user = new UserMc($row['idUser'], $rowsJob, $row["name"], $row["lastName"], $row["email"], $row["photo"], $row["username"], $row["secretQuestion"], $row["activeUser"], $isAdministrator);
		array_push($usersArray, $user);
	}

	return $usersArray;
}
?>