<?php
/**
 * /_public-html/_model/tasks-show-m.php
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 17:15
 * Campinas-SP | Brazil
 */
include_once "../_commons/includes-inside.php";

function getTestWithTask($idTask) {
	//Create queries
	$query = " SELECT idTask FROM " . ValidateQueriesMc::$dbName . ".tbTests WHERE idTask = ? ";
	//Get datas
	$rows = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	$taskArray = array();

	//Not found datas
	if ($rows === false) {
		return $taskArray;
	}

	//Get data
	foreach ($rows as $row) {
		array_push($taskArray, $row["idTask"]);
	}

	return $taskArray;
}

function saveTesters($tester) {

	//Create queries
	$query = "INSERT INTO " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers(idTest,idUser) ";
	$query .= " VALUES $tester";

	//Get tasks datas
	$rows = ValidateQueriesMc::StartQuery($query);

	//Not found datas
	if ($rows === false) {
		return false;
	}

	return true;
}

function getTestersAtTask($idTask) {
	//Create queries
	$query = " SELECT testsXusers.* ";
	$query .= " FROM " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers as testsXusers , " . ValidateQueriesMc::$dbName . ".tbTests as tests ";
	$query .= " WHERE tests.idTask = $idTask ";
	$query .= " AND testsXusers.idTest = tests.idTest;";
	//Get datas
	$rows = ValidateQueriesMc::StartQuery($query);

	$usersArray = array();

	//Not found datas
	if ($rows === false) {
		return $usersArray;
	}

	//Get data
	foreach ($rows as $row) {
		array_push($usersArray, $row["idUser"]);
	}

	return $usersArray;
}

function rollbackNextPhase($idTask) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET idTaskStatus = 2 WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function returnDevelopment($idTask) {
	//Now next ever be a "TEST", in future, Next will be at other task (dynamic)

	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET idTaskStatus = 2 WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	//if task changed, need change date last-update
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET updateDate = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	//if task changed, need remove testers
	$query = " DELETE FROM " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers WHERE idTest = (SELECT idTest FROM tbTests WHERE idTask = ?) ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function nextPhase($idTask) {
	//Now next ever be a "TEST", in future, Next will be at other task (dynamic)

	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET idTaskStatus = 3 WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	//if task changed, need change date last-update
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET updateDate = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	//if task changed, need change date test last-update
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTests SET testDate = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function getUsersEmail($idUsers) {
	//Create queries
	$query = " SELECT * FROM " . ValidateQueriesMc::$dbName . ".tbUsers WHERE idUser IN ($idUsers); ";
	//Get datas
	$rows = ValidateQueriesMc::StartQuery($query);

	$usersArray = array();

	//Not found datas
	if ($rows === false) {
		return $usersArray;
	}

	//Get data
	foreach ($rows as $row) {
		array_push($usersArray, $row["email"]);
	}

	return $usersArray;
}

function postComment($idTask, $idUser, $comment) {
	//Create queries
	$query = " INSERT INTO " . ValidateQueriesMc::$dbName . ".tbTasksComments(idTask,idUser,commentText,dateComment,activeComment,deletTime) ";
	$query .= " VALUES ( ?,?,?,now(),?,? ) ";

	//Get tasks datas

	$rowsComments = ValidateQueriesMc::StartQuery($query, array("$idTask", "$idUser", "$comment", "1", null));

	//Not found datas
	if ($rowsComments === false) {
		return false;
	}

	return true;
}

function getComments($idTask) {
	//Create queries
	$query = " SELECT comments.*, concat(users.name ,' ', users.lastName) as completeName ";
	$query .= " FROM " . ValidateQueriesMc::$dbName . ".tbTasksComments comments, " . ValidateQueriesMc::$dbName . ".tbUsers users ";
	$query .= " WHERE users.idUser = comments.idUser ";
	$query .= " AND activeComment = 1";
	$query .= " AND idTask = ? ";
	$query .= " ORDER BY idComment desc ";

	//Get tasks datas
	$rowsComments = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	$commentsArray = array();

	//Not found datas
	if ($rowsComments === false) {
		return $commentsArray;
	}

	//Get data
	foreach ($rowsComments as $row) {
		array_push($commentsArray, new TasksCommentskMc($row["idComment"], $row["idTask"], $row["idUser"], $row["commentText"], $row["dateComment"], $row["activeComment"], $row["deletTime"], $row["completeName"]));
	}

	return $commentsArray;
}

function getStatus() {
	//Create queries
	$query = "SELECT * FROM " . ValidateQueriesMc::$dbName . ".tbStatus";

	//Get tasks datas
	$rowsStatus = ValidateQueriesMc::StartQuery($query);

	$statusArray = array();

	//Not found datas
	if ($rowsStatus === false) {
		return $statusArray;
	}

	//Get data
	foreach ($rowsStatus as $row) {
		array_push($statusArray, $row["taskStatus"]);
	}

	return $statusArray;
}

function requestDeveloper($idTask, $idUser) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET idUserDevelop = ?, idTaskStatus = 2 WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idUser", "$idTask"));

	if ($response == false) {
		return false;
	}

	//if task changed, need change date last-update
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET updateDate = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function transferAnotherUser($idTask, $idUser) {

	$newStatus = " ";

	if ($idUser < 0) {
		$idUser = null;
		$newStatus = ",idTaskStatus = 1";
	}

	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks ";
	$query .= " SET idUserDevelop = ? ";
	$query .= $newStatus;
	$query .= " WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array($idUser, "$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function deletTask($idTask) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET activeTask = 0, deletTime = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function deletComment($idComment) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasksComments SET activeComment = 0, deletTime = now() WHERE idComment = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idComment"));

	if ($response == false) {
		return false;
	}

	return true;
}

function rollbackDeletTask($idTask) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks SET activeTask = 1, deletTime = null WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	return true;
}

function rollbackDeletComment($idComment) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasksComments SET activeComment = 1, deletTime = null WHERE idComment = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idComment"));

	if ($response == false) {
		return false;
	}

	return true;
}

function getTasks($status = null, $onlyMyTasksDevelop = null, $onlyMyTasksRequest = null) {

	if ($status == null)
		$status = " ";
	else
		$status = " AND tasks.idTaskStatus = '$status' ";

	if ($onlyMyTasksDevelop == null)
		$onlyMyTasksDevelop = " ";
	else
		$onlyMyTasksDevelop = " AND tasks.idUserDevelop  = '$onlyMyTasksDevelop' ";

	if ($onlyMyTasksRequest == null)
		$onlyMyTasksRequest = " ";
	else
		$onlyMyTasksRequest = " AND tasks.idUserCreation  = '$onlyMyTasksRequest' ";

	//Create queries
	$query = " SELECT tasks.*, concat(users.name,' ',users.lastName) as nameUserCreation, ";
	$query .= " CASE WHEN tasks.idUserDevelop IS NULL THEN '[OPEN]' ELSE concat(usersDevelop.name,' ',usersDevelop.lastName) end as nameUserDevelop, ";
	$query .= " jobs.job as jobName, ";
	$query .= " status.taskStatus as statusName ";
	$query .= "  FROM " . ValidateQueriesMc::$dbName . ".tbTasks tasks, ";
	$query .= "  " . ValidateQueriesMc::$dbName . ".tbUsers users, ";
	$query .= "  " . ValidateQueriesMc::$dbName . ".tbUsers usersDevelop,  ";
	$query .= "  " . ValidateQueriesMc::$dbName . ".tbStatus status, ";
	$query .= "  " . ValidateQueriesMc::$dbName . ".tbJobs jobs ";
	$query .= " WHERE users.idUser = tasks.idUserCreation ";
	$query .= " AND (usersDevelop.idUser = tasks.idUserDevelop OR tasks.idUserDevelop IS NULL) ";
	$query .= " AND status.idTaskStatus = tasks.idTaskStatus ";
	$query .= " AND jobs.idJob = tasks.idJob ";
	$query .= " AND tasks.activeTask = 1 ";
	$query .= $status;
	$query .= $onlyMyTasksDevelop;
	$query .= $onlyMyTasksRequest;
	$query .= " GROUP BY idTask; ";
	//Get tasks datas

	$rowsTasks = ValidateQueriesMc::StartQuery($query);
	$tasksArray = array();

	//Not found datas
	if ($rowsTasks === false) {
		return $tasksArray;
	}

	//Get users
	foreach ($rowsTasks as $row) {
		$task = new TaskMc($row['idTask'], $row['idJob'], $row['idTaskStatus'], $row['name'], $row['description'], $row['createDate'], $row['updateDate'], $row['idUserDevelop'], $row['idUserCreation'], $row['hoursPlanned'], $row['hoursReal'], $row['nameUserCreation'], $row['nameUserDevelop'], $row['jobName'], $row['statusName']);
		array_push($tasksArray, $task);
	}

	return $tasksArray;
}
?>