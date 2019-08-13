<?php
/**
 * /_public-html/_model/users-show-m.php
 *
 *
 * Search for all users on database
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 17:15
 * Campinas-SP | Brazil
 */
if (!isset($_SESSION))
	session_start();
include_once $_SESSION["PATH_SERVER"] . "/_commons/includes-inside.php";
include_once $_SESSION["PATH_SERVER"] . "/_model/JobMc.php";

/*
 * Enable User
 */
function enableUser($idUser) {
	$query = "UPDATE " . ValidateQueriesMc::$dbName . ".tbUsers SET activeUser = 1 WHERE idUser = ?";
	$response = ValidateQueriesMc::StartQuery($query, array("$idUser"));

	return true;
}

/*
 * Disable User
 */
function disableUser($idUser) {
	$query = "UPDATE " . ValidateQueriesMc::$dbName . ".tbUsers SET activeUser = 0 WHERE idUser = ?";
	$response = ValidateQueriesMc::StartQuery($query, array("$idUser"));

	return true;
}

/*
 * Get all users with jobs
 * Pass one idUser to ignore
 */
function getUsers($idUser = null) {

	if ($idUser != null)
		$idUser = " WHERE idUser != '$idUser' ";
	else
		$idUser = " ";

	//Create queries
	$queryUsers = "SELECT * FROM " . ValidateQueriesMc::$dbName . ".tbUsers";
	$queryUsers .= $idUser;
	$queryJobs = "SELECT u.idUser, j.idJob, j.job FROM " . ValidateQueriesMc::$dbName . ".tbJobs j, " . ValidateQueriesMc::$dbName . ".tbUsers u, " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs uj ";
	$queryJobs .= "WHERE j.idJob = uj.idJob and u.idUser = uj.idUser;";

	//Get user datas
	$rowsUsers = ValidateQueriesMc::StartQuery($queryUsers);
	//Get user jobs
	$rowsJobs = ValidateQueriesMc::StartQuery($queryJobs);

	$usersArray = array();

	//Not found datas
	if ($rowsUsers === false || $rowsJobs === false) {
		return $usersArray;
	}

	//Get users
	foreach ($rowsUsers as $rowU) {
		$idUserJob = array();
		foreach ($rowsJobs as $rowJ) {
			$jobs = new JobMc($rowJ['idUser'], $rowJ['idJob'], $rowJ['job']);
			//Get jobs of each user
			if ($rowU['idUser'] == $rowJ['idUser']) {
				array_push($idUserJob, $jobs);
			}
		}

		$isAdministrator = false;
		for ($count = 0; $count < sizeof($idUserJob); $count++) {
			if ($idUserJob[$count] -> job == "Administrator")
				$isAdministrator = true;
		}

		$user = new UserMc($rowU['idUser'], $idUserJob, $rowU["name"], $rowU["lastName"], $rowU["email"], $rowU["photo"], $rowU["username"], $rowU["secretQuestion"], $rowU["activeUser"], $isAdministrator);
		array_push($usersArray, $user);
		unset($idUserJob);
	}

	return $usersArray;
}
?>