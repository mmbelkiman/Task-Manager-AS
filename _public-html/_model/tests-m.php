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

function deletComment($idComment) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTestsComments SET activeComment = 0, deletTime = now() WHERE idComment = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idComment"));

	if ($response == false) {
		return false;
	}

	return true;
}

function rollbackDeletComment($idComment) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTestsComments SET activeComment = 1, deletTime = null WHERE idComment = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idComment"));

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

function postComment($idTest, $idUser, $comment) {
	//Create queries
	$query = " INSERT INTO " . ValidateQueriesMc::$dbName . ".tbTestsComments(idTest,idUser,commentText,dateComment,activeComment,deletTime) ";
	$query .= " VALUES ( ?,?,?,now(),?,? ) ";

	//Get tasks datas

	$rowsComments = ValidateQueriesMc::StartQuery($query, array("$idTest", "$idUser", "$comment", "1", null));

	//Not found datas
	if ($rowsComments === false) {
		return false;
	}

	return true;
}

function getComments($idTest) {
	//Create queries
	$query = " SELECT comments.*, concat(users.name ,' ', users.lastName) as completeName ";
	$query .= " FROM " . ValidateQueriesMc::$dbName . ".tbTestsComments comments, " . ValidateQueriesMc::$dbName . ".tbUsers users ";
	$query .= " WHERE users.idUser = comments.idUser ";
	$query .= " AND activeComment = 1";
	$query .= " AND idTest = ? ";
	$query .= " ORDER BY idComment desc ";

	//Get tasks datas
	$rowsComments = ValidateQueriesMc::StartQuery($query, array("$idTest"));

	$commentsArray = array();

	//Not found datas
	if ($rowsComments === false) {
		return $commentsArray;
	}

	//Get data
	foreach ($rowsComments as $row) {
		array_push($commentsArray, new TestsCommentskMc($row["idComment"], $row["idTest"], $row["idUser"], $row["commentText"], $row["dateComment"], $row["activeComment"], $row["deletTime"], $row["completeName"]));
	}

	return $commentsArray;
}

function setStatus($status, $idTest, $idUser) {
	$query = " UPDATE " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers SET idTestStatus = (SELECT ts.idTestStatus FROM " . ValidateQueriesMc::$dbName . ".tbTestsStatus ts WHERE ts.testStatus = ?) WHERE idTest = ? AND idUser = ? ";

	$response = ValidateQueriesMc::StartQuery($query, array("$status", "$idTest", "$idUser"));

	if ($response == false) {
		return false;
	}

	return true;
}

function getStatus() {
	//Create queries
	$query = "SELECT * FROM " . ValidateQueriesMc::$dbName . ".tbTestsStatus";

	//Get tasks datas
	$rowsStatus = ValidateQueriesMc::StartQuery($query);

	$statusArray = array();

	//Not found datas
	if ($rowsStatus === false) {
		return $statusArray;
	}

	//Get data
	foreach ($rowsStatus as $row) {
		array_push($statusArray, $row["testStatus"]);
	}

	return $statusArray;
}

function getTests($statusId = null, $needAnswering = null) {

	$showTest = false;

	if ($needAnswering == null) {
		$showTest = true;
	} else {
		$userId = $needAnswering;
	}

	//Create queries
	$query = " SELECT tests.idTest as idTest, ";
	$query .= " concat(tasks.idTask,' - ',tasks.name) as name, ";
	$query .= " tasks.idTaskStatus as idTaskStatus, ";
	$query .= " tasks.description as description, ";
	$query .= " tests.testDate as testDate, ";
	$query .= " tests.testDateReady as testDateReady ";
	$query .= " FROM " . ValidateQueriesMc::$dbName . ".tbTests tests, " . ValidateQueriesMc::$dbName . ".tbTasks tasks ";
	$query .= " WHERE tasks.idTask = tests.idTask ";
	$query .= " GROUP BY tests.idTest; ";

	$rowsTests = ValidateQueriesMc::StartQuery($query);
	$testsArray = array();

	//Not found datas
	if ($rowsTests === false) {
		return $testsArray;
	}

	$queryUsers = " SELECT tests.* ,";
	$queryUsers .= " concat(users.name, ' ', users.lastName) as fullName ";
	$queryUsers .= " FROM " . ValidateQueriesMc::$dbName . ".tbTestsXtbUsers as tests, " . ValidateQueriesMc::$dbName . ".tbUsers as users ";
	$queryUsers .= " WHERE tests.idUser = users.idUser ";
	$queryUsers .= " Order By idUser; ";

	$rowsUsers = ValidateQueriesMc::StartQuery($queryUsers);
	$UsersArray = array();

	//Get users
	if ($rowsUsers !== false) {
		foreach ($rowsUsers as $rowUser) {
			$user = array($rowUser['idTest'], $rowUser['idUser'], $rowUser['idTestStatus'], $rowUser['testDateChange'], $rowUser['fullName']);
			array_push($rowsUsers, $user);
		}
	}

	//Get tests
	foreach ($rowsTests as $row) {

		$mUsersId = array();
		$mUsersName = array();
		$mUsersStatus = array();
		$status = "Analysis";
		$countApproved = 0;
		$countTesters = 0;

		for ($count = 0; $count < count($rowsUsers); $count++) {
			if (isset($rowsUsers[$count]["idTest"]))
				if ($rowsUsers[$count]["idTest"] == $row['idTest']) {
					array_push($mUsersId, $rowsUsers[$count]["idUser"]);
					array_push($mUsersName, $rowsUsers[$count]["fullName"]);
					array_push($mUsersStatus, $rowsUsers[$count]["idTestStatus"]);
					$countTesters++;

					if ($rowsUsers[$count]["idTestStatus"] == 3)
						$status = "Disapproved";

					if ($rowsUsers[$count]["idTestStatus"] == 2)
						$countApproved++;
				}
		}

		//Don't have testers, no show
		if ($countTesters == 0) {
			$showTest = false;
		}

		//Verify test is approved
		if ($countApproved == $countTesters)
			$status = "Approved";

		for ($countValid = 0; $countValid < 4; $countValid++) {
			if (!isset($mUsersName[$countValid])) {
				$mUsersId[$countValid] = "-";
				$mUsersName[$countValid] = "-";
				$mUsersStatus[$countValid] = "-";
			} else {
				//If request show only tests you need do, verify here you are a tester
				if ($needAnswering != null)
					if ($mUsersId[$countValid] == $userId) {
						$showTest = true;
					}
			}
		}

		//Request order by status
		if ($statusId != null) {
			switch ($statusId) {
				case '1' :
					if ($status != "Analysis")
						$showTest = false;
					break;
				case '2' :
					if ($status != "Approved")
						$showTest = false;
					break;
				case '3' :
					if ($status != "Disapproved")
						$showTest = false;
					break;
			}
		}

		if ($showTest) {
			$test = new TestsMc($row['idTest'], $row['name'], $row['description'], $row['testDate'], $row['testDateReady'], $mUsersId[0], $mUsersName[0], $mUsersId[1], $mUsersName[1], $mUsersId[2], $mUsersName[2], $mUsersId[3], $mUsersName[3], $mUsersStatus[0], $mUsersStatus[1], $mUsersStatus[2], $mUsersStatus[3], $status, $row["idTaskStatus"]);
			array_push($testsArray, $test);
		}
	}

	return $testsArray;
}
?>