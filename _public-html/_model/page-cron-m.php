<?php

/*
 * Save Cron
 */
function setCron($idTask, $time) {
	$query = " UPDATE " .ValidateQueriesMc::$dbName .".tbTasks SET hoursReal = '$time' WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	if ($response == false) {
		return false;
	}

	//if cron changed, need change date last-update
	$query = " UPDATE " .ValidateQueriesMc::$dbName .".tbTasks SET updateDate = now() WHERE idTask = ? ";
	$response = ValidateQueriesMc::StartQuery($query, array("$idTask"));

	return true;
}
?>