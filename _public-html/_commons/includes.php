<!-- Includes->PHP -->
<?php
session_start();
include_once "_commons/_php/global-variables.php";
include_once "_commons/_php/current-page-url.php";
include_once "_commons/_php/ValidateQueriesMc.php";
include_once "_commons/_php/validate-login.php";
include_once "_commons/_php/PHPMailerMc.php";

if (isset($_SESSION["User"])) {
	include_once "_view/_pages/page-menu.php";
}

if (isset($_SESSION["Cron"])) {
	include_once '_view/_pages/page-cron.php';
}
?>
<!--Close Includes->PHP -->
