<?php
/**
 * /_public-html/_model/home-m.php
 *
 *
 *
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.2
 * @date       2014/07/10 : 20:19
 * Campinas-SP | Brazil
 */
include_once "../_commons/includes-inside.php";

/*
 * Save reminds
 */
function setReminds($idUser, $reminders) {
	$query = "UPDATE " . ValidateQueriesMc::$dbName . ".tbReminders SET reminder = '$reminders' WHERE idUser = ?";
	$response = ValidateQueriesMc::StartQuery($query, array("$idUser"));

	return $response;
}

/*
 * Load reminds
 */
function getReminds($idUser) {
	$query = "SELECT * FROM " . ValidateQueriesMc::$dbName . ".tbReminders WHERE idUser = ?";

	$rows = array();
	$rows = ValidateQueriesMc::StartQuery($query, array("$idUser"));

	$remindsArray = array();

	//Not found datas
	if ($rows === false) {
		return $remindsArray;
	}

	foreach ($rows as $row) {
		array_push($remindsArray, $row["reminder"]);
	}

	return $remindsArray;
}

function getCountTasksAnalyse($idUser) {
	$query = " SELECT COUNT(idTest) as tasksCount FROM " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers ";
	$query .= " WHERE idUser = ? AND (idTestStatus = ? OR idTestStatus = '' OR idTestStatus IS NULL) ";

	$countResult = ValidateQueriesMc::StartQuery($query, array("$idUser", "1"));

	$tasksArray = array();

	//Not found datas
	if ($countResult === false) {
		return $tasksArray;
	}

	foreach ($countResult as $row) {
		array_push($tasksArray, $row["tasksCount"]);
	}

	return $tasksArray;
}

function getCountTasksDevelop($idUser) {
	$query = " SELECT COUNT(t.idTaskStatus) as tasksCount FROM " . ValidateQueriesMc::$dbName . ".tbTasks t ";
	$query .= " WHERE t.activeTask = ? AND t.idUserDevelop = ? AND (t.idTaskStatus = ? OR t.idTaskStatus = ?)";

	$countResult = ValidateQueriesMc::StartQuery($query, array("1", "$idUser", "2", "3"));

	$tasksArray = array();

	//Not found datas
	if ($countResult === false) {
		return $tasksArray;
	}

	foreach ($countResult as $row) {
		array_push($tasksArray, $row["tasksCount"]);
	}

	return $tasksArray;
}

function getCountTasksOpen($idUser) {
	$query = " SELECT COUNT(t.idTaskStatus) as tasksCount FROM " . ValidateQueriesMc::$dbName . ".tbTasks t ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs uj ON uj.idJob = t.idJob ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbUsers u ON u.idUser = uj.idUser ";
	$query .= " WHERE t.idTaskStatus = ? AND t.activeTask = ? AND u.idUser = ? ";

	$countResult = ValidateQueriesMc::StartQuery($query, array("1", "1", "$idUser"));

	$tasksArray = array();

	//Not found datas
	if ($countResult === false) {
		return $tasksArray;
	}

	foreach ($countResult as $row) {
		array_push($tasksArray, $row["tasksCount"]);
	}

	return $tasksArray;
}

function getTasksDisapproval($idUser) {
	$query = " SELECT name FROM " . ValidateQueriesMc::$dbName . ".tbTasks WHERE idTask IN ";
	$query .= " ( ";
	$query .= " SELECT tk.idTask FROM " . ValidateQueriesMc::$dbName . ".tbTasks tk ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTests ts ON ts.idTask = tk.idTask ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers tu ON tu.idTest = ts.idTest ";
	$query .= " WHERE tk.activeTask = ? AND tk.idUserDevelop = ? AND tu.idTestStatus = ? ";
	$query .= " ) ";

	$result = ValidateQueriesMc::StartQuery($query, array("1", "$idUser", "3"));

	$tasksArray = array();

	//Not found datas
	if ($result === false) {
		return $tasksArray;
	}
	foreach ($result as $row) {
		array_push($tasksArray, $row["name"]);
	}

	return $tasksArray;
}

function getTasksApproval($idUser) {
	$query = " SELECT distinct(ttk.idTask), ttk.name FROM " . ValidateQueriesMc::$dbName . ".tbTasks ttk ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTests tts ON tts.idTask = ttk.idTask ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers ttu ON ttu.idTest = tts.idTest ";
	$query .= " WHERE ttk.idTask IN ";
	$query .= " ( ";
	$query .= " SELECT tk.idTask FROM " . ValidateQueriesMc::$dbName . ".tbTasks tk ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTests ts ON ts.idTask = tk.idTask ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers tu ON tu.idTest = ts.idTest ";
	$query .= " WHERE tk.activeTask = ? AND tk.idUserDevelop = ? AND tu.idTestStatus = ? AND tk.idTask NOT IN ";
	$query .= " ( ";
	$query .= " SELECT tk.idTask FROM " . ValidateQueriesMc::$dbName . ".tbTasks tk ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTests ts ON ts.idTask = tk.idTask ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers tu ON tu.idTest = ts.idTest ";
	$query .= " WHERE tk.activeTask = ? AND tk.idUserDevelop = ? AND (tu.idTestStatus = ? OR tu.idTestStatus = ? OR tu.idTestStatus IS NULL)";
	$query .= " ) ";
	$query .= " ) ";
	$query .= " AND ttk.idTaskStatus = ? ";

	$result = ValidateQueriesMc::StartQuery($query, array("1", "$idUser", "2", "1", "$idUser", "3", "1", "3"));

	$tasksArray = array();

	//Not found datas
	if ($result === false) {
		return $tasksArray;
	}
	foreach ($result as $row) {
		array_push($tasksArray, $row["idTask"], $row["name"]);
	}

	return $tasksArray;
}

function insertCommit($idTask, $files, $dateUpdate, $dateUpload) {
	$query = " INSERT INTO " . ValidateQueriesMc::$dbName . ".tbCommits (idTask,files,dateUpdate,dateUpload) ";
	$query .= " VALUES (?,?,?,?); ";

	$result = ValidateQueriesMc::StartQuery($query, array("$idTask", "$files", "$dateUpdate", "$dateUpload"));

	if ($result === false) {
		return false;
	}

	return true;
}

function getCommitsNotFinalized($idUser) {
	include_once "../_model/CommitMc.php";
	//$idAdministratorJob = "6";

	/*
	 $validate = " SELECT COUNT(idUser) as CountResult ";
	 $validate .= " FROM " . ValidateQueriesMc::$dbName . ".tbUsersXtbJobs ";
	 $validate .= " WHERE idUser = ? AND idJob = ? ";
	 $resultValidate = ValidateQueriesMc::StartQuery($validate, array("$idUser", $idAdministratorJob));
	 */
	$arrayCommit = array();

	//if ($resultValidate === false) {}else{
	$query = " SELECT c.idCommit, t.name , c.files, c.dateUpdate ";
	$query .= " FROM " . ValidateQueriesMc::$dbName . ".tbCommits c ";
	$query .= " INNER JOIN " . ValidateQueriesMc::$dbName . ".tbTasks t ON c.idTask = t.idTask ";
	$query .= " WHERE dateUpload IS NULL ";
	$query .= " ORDER BY dateUpdate DESC ";
	$resultQueryCommits = ValidateQueriesMc::StartQuery($query);

	foreach ($resultQueryCommits as $row) {
		$commits = new CommitMc($row["idCommit"], $row["name"], $row["files"], $row["dateUpdate"]);
		array_push($arrayCommit, $commits);
	}
	//}

	return $arrayCommit;
}

function updateDateUploadByCommit($idCommit) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbCommits ";
	$query .= " SET dateUpload = now() ";
	$query .= " WHERE idCommit = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idCommit"));

	return $response;
}

function insertCommits($idTask, $files) {
	$query = " INSERT INTO " . ValidateQueriesMc::$dbName . ".tbCommits ";
	$query .= "(idTask,files,dateUpdate) VALUES (?, ?, now()); ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask", "$files"));

	return $response;
}

function updateCommits($idTask) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTasks ";
	$query .= " SET idTaskStatus = ? WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("4", "$idTask"));

	return $response;
}
?>