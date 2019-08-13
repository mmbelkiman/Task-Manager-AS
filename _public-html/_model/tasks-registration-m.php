<?php
/**
 * /_public-html/_model/tasks-registration-m.php
 *
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/30 : 22:10
 * Campinas-SP | Brazil
 */
include_once "../_commons/includes-inside.php";
include_once "../_model/JobMc.php";

/*
 * Search for all jobs on database
 */
function getJobs() {

	//Create queries
	$queryJobs = "SELECT idJob, job FROM " .ValidateQueriesMc::$dbName .".tbJobs";

	//Get user jobs
	$rowsJobs = ValidateQueriesMc::StartQuery($queryJobs);

	//Not found datas
	if ($rowsJobs === false) {
		return $usersArray;
	}

	$jobsArray = array();

	//Populate array with jobs
	foreach ($rowsJobs as $row) {
		$job = new JobMc("", $row["idJob"], $row["job"]);
		array_push($jobsArray, $job);
	}

	return $jobsArray;
}

function insertTasks($idJob, $name, $description, $idUserCreation, $datetime) {
	//Create queries

	$queryTask = " INSERT INTO " .ValidateQueriesMc::$dbName .".tbTasks ";
	$queryTask .= " (idJob,idTaskStatus,name,description,createDate,updateDate,idUserCreation,hoursPlanned,activeTask) ";
	$queryTask .= " VALUES (?,?,?,?,NOW(),NOW(),?,?,?) ";

	$resultQueryInsertTask = ValidateQueriesMc::StartQuery($queryTask, array($idJob, "1", $name, $description, $idUserCreation, $datetime, "1"));
	if ($resultQueryInsertTask === false) {
		return false;
	}

	//Create Test indexed (in future, tests will be created separeted... 1 task have N tests...)
	$queryTest = " INSERT INTO " .ValidateQueriesMc::$dbName .".tbTests ";
	$queryTest .= " (idTask) ";
	$queryTest .= " VALUES (?) ";

	$resultQueryInsertTest = ValidateQueriesMc::StartQuery($queryTest, array($resultQueryInsertTask));
	if ($resultQueryInsertTest === false) {
		return false;
	}

	return true;
}
?>