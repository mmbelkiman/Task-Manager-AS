<?php
if (!isset($_SESSION))
	session_start();

include_once $_SESSION["PATH_SERVER"] ."/_commons/_php/global-variables.php";
include_once $_SESSION["PATH_SERVER"] ."/_commons/_php/ValidateQueriesMc.php";
include_once $_SESSION["PATH_SERVER"] ."/_commons/_php/PHPMailerMc.php";
?>