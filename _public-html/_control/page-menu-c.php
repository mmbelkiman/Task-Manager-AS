<?php
/**
 * /_public-html/_control/page-menu-c.php
 *
 *
 * 
 *
 * @package    _control
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 12:21
 * Campinas-SP | Brazil
 */
session_start();
include_once ("../_model/UserMc.php");

if (isset($_POST["logOut"])) {
	$_SESSION = array();
	session_destroy();
	echo json_encode('Log-Out successful');
}

if (isset($_POST["getUserName"])) {
	echo json_encode( unserialize($_SESSION["User"]) -> name . " " . unserialize($_SESSION["User"]) -> lastName . " @" . unserialize($_SESSION["User"]) -> username);
}
?>