<?php
if (isset($_POST["getCron"])) {
	include_once ("../_model/CronMc.php");
	include_once "../_commons/includes-inside.php";

	if (isset($_SESSION["Cron"])) {
		echo json_encode(unserialize($_SESSION["Cron"]));
		return;
	}

	echo json_encode("No Cron Settings Finded");
	return;
}

if (isset($_POST["saveCron"])) {
	include_once ("../_model/CronMc.php");
	include_once ("../_model/page-cron-m.php");
	include_once "../_commons/includes-inside.php";

	$mCron = unserialize($_SESSION["Cron"]);
	$mTime = $_COOKIE["hours"] . ":" . $_COOKIE["minutes"] . ":" . $_COOKIE["seconds"];

	setCron($mCron[0] -> idTask, $mTime);

	echo json_encode("Cron Saved");

	return;
}

if (isset($_POST["enableDisableCron"])) {
	include_once ("../_model/CronMc.php");
	include_once ("../_model/page-cron-m.php");
	include_once "../_commons/includes-inside.php";

	if (isset($_SESSION["Cron"])) {
		$mCron = unserialize($_SESSION["Cron"]);
		$mTime = $_COOKIE["hours"] . ":" . $_COOKIE["minutes"] . ":" . $_COOKIE["seconds"];

		setCron($mCron[0] -> idTask, $mTime);
		unset($_SESSION["Cron"]);

		echo json_encode("Cron Disable");
	} else {
		$mCron = array();

		array_push($mCron, new CronMc($_POST["idTask"], $_POST["nameTask"], $_POST["timeTask"]));
		$_SESSION["Cron"] = serialize($mCron);

		echo json_encode("Cron Enable");
	}

	return;
}
?>