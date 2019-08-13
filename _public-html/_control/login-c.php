<?php
/**
 * /_public-html/_control/login-c.php
 *
 *
 * Control that treatment the user's login
 *
 * @package    _control
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.1
 * @date       2014/07/02 : 21:54
 * Campinas-SP | Brazil
 */
if (isset($_POST["getLoginSuccess"])) {
	//Includes
	include_once ("../_model/UserMc.php");
	include_once ("../_model/login-m.php");
	include_once ("../_commons/_php/encrypt.php");

	//It is necessary decrypt the password of cookies
	if (isset($_POST["cookies"])) {
		$encryptPassword = startEncrypt(base64_decode($_POST["password"]));
	} else {
		$encryptPassword = startEncrypt($_POST["password"]);
	}
	
	//Get result of select
	$usersArray = getUserByUsernamePassword($_POST["user"], $encryptPassword);

	//Not found datas
	if (count($usersArray) == 0) {
		//Count errors of account validation
		if (!isset($_SESSION["CountErrors"])) {
			$countErrors = 0;
		} else {
			$countErrors = unserialize($_SESSION["CountErrors"]);
		}
		$countErrors++;

		//Set errors in the array
		array_push($usersArray, array("error" => "Invalid user or password =("));
		array_push($usersArray, array("countError" => $countErrors));

		//Create session with count of errors
		$_SESSION["CountErrors"] = serialize($countErrors);
	}
	//Contains datas then serialize
	else {
		unset($_SESSION["CountErrors"]);
		$_SESSION["User"] = serialize($usersArray[0]);
		array_push($usersArray, array("passwordUser" => base64_encode($_POST["password"])));
	}

	echo json_encode($usersArray);
	return;
}

//Verify session and cookies of users and the amount of errors accumulated
if (isset($_POST["getOnlySessionCountErrors"])) {
	session_start();

	$usersArray = array();

	if (isset($_COOKIE["CountErrors"])) {
	} else {
		setcookie("CountErrors", "Started", time()+31556926);
		$_SESSION["CountErrors"] = serialize(5);
	}

	if (isset($_SESSION["CountErrors"])) {
		array_push($usersArray, array("countError" => unserialize($_SESSION["CountErrors"])));
	} else {
		array_push($usersArray, array("validate" => "true"));
	}

	echo json_encode($usersArray);
	return;
}

?>