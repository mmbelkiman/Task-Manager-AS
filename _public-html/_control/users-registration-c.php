<?php
/**
 * /_public-html/_control/users-registration-c.php
 *
 *
 * Control that treatment the user registration
 *
 * @package    _control
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.2
 * @date       2014/06/28 : 13:31
 * Campinas-SP | Brazil
 */

//Get all jobs
if (isset($_POST["getJobs"])) {
	include_once ("../_model/users-registration-m.php");

	$jobsArray = getJobs();

	//Not found datas
	if (count($jobsArray) == "") {
		array_push($jobsArray, array("error" => "No users registered"));
	} else {
		//Finded :-)
	}

	echo json_encode($jobsArray);

	return;
}

//Insert a new user
if (isset($_POST["insertUser"])) {
	include_once "../_model/users-registration-m.php";
	include_once "../_commons/_php/encrypt.php";

	$userArray = array();
	$encryptPassword = startEncrypt($_POST["password"]);
	if (isset($_POST["idUser"])) {
		if ($_POST["idUser"] != "") {
			$returnInsertOrUpdate = updateUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["arrayJobs"], $_POST["username"], $encryptPassword, $_POST["secretQuestion"], $_POST["secretAnswer"], $_POST["idUser"]);
		} else {
			$returnInsertOrUpdate = insertUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["arrayJobs"], $_POST["username"], $encryptPassword, $_POST["secretQuestion"], $_POST["secretAnswer"]);
		}
	} else {
		$returnInsertOrUpdate = insertUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["arrayJobs"], $_POST["username"], $encryptPassword, $_POST["secretQuestion"], $_POST["secretAnswer"]);
	}

	if ($returnInsertOrUpdate === false) {
		array_push($userArray, array("error" => "Fail to register/update a user =("));
	}

	if (count($userArray) == "") {
		array_push($userArray, array("validate" => "true"));
		array_push($userArray, array("idUser" => $returnInsertOrUpdate));
	}
	echo json_encode($userArray);
	return;
}

//Get user by id
if (isset($_POST["getUserById"])) {
	include_once ("../_model/users-registration-m.php");

	$userArray = getUserById($_POST["idUser"]);

	//Not found datas
	if (count($userArray) == "") {
		array_push($userArray, array("error" => "No users registered"));
	} else {
		//Finded :-)
	}

	echo json_encode($userArray);

	return;
}

if (isset($_POST["validateAllUserFields"])) {
	include_once ("../_model/users-registration-m.php");

	$idUser = "";
	//Get cookie and verify if register or update
	if (isset($_POST["idUser"])) {
		$idUser = $_POST["idUser"];
	}

	$statusArray = array();

	if (isset($_POST["selectField"])) {
		if ($_POST["selectField"] == "firstNameRegistration") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a first name."));
			}
		}
		if ($_POST["selectField"] == "lastNameRegistration") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a last name."));
			}
		}
		if ($_POST["selectField"] == "emailRegistration") {
			$email = trim($_POST["valueField"]);
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if ($email == "") {
				array_push($statusArray, array("error" => "Please, fill a email."));
			} else if (!preg_match($regex, $email, $match)) {
				array_push($statusArray, array("error" => "Please, fill a correct email."));
			} else {
				if ($idUser == "") {
					if (getEmailFromUsersByEmail($email) == true) {
						array_push($statusArray, array("error" => "Email already exists."));
					}
				}
			}
		}
		if ($_POST["selectField"] == "usernameRegistration") {
			$username = trim($_POST["valueField"]);
			if ($username == "") {
				array_push($statusArray, array("error" => "Please, fill a username."));
			} else {
				if ($idUser == "") {
					if (getUsernameFromUsersByUsername($username) == true) {
						array_push($statusArray, array("error" => "Username already exists."));
					}
				}
			}
		}
		if ($_POST["selectField"] == "passwordRegistration") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a password."));
			} else if ($_POST["confirmPasswordsField"] != $_POST["confirmPasswordsAgainField"]) {
				array_push($statusArray, array("error" => "Passwords not coincide."));
			}
		}
		if ($_POST["selectField"] == "passwordAgainRegistration") {
			if (trim($_POST["valueField"]) == "") {
				array_push($statusArray, array("error" => "Please, fill a password."));
			} else if ($_POST["confirmPasswordsField"] != $_POST["confirmPasswordsAgainField"]) {
				array_push($statusArray, array("error" => "Passwords not coincide."));
			}
		}

	}

	if (count($statusArray) == "") {
		array_push($statusArray, array("validate" => "true"));
	}

	echo json_encode($statusArray);

	return;
}
?>