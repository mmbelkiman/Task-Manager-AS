<?php
if (isset($_POST["getUsers"])) {
	include_once ("../_model/UserMc.php");
	include_once ("../_model/users-show-m.php");

	$usersArray = getUsers();

	//Not found datas
	if (count($usersArray) == 0) {
		array_push($usersArray, array("erro" => "No users registred"));
	} else {
		//Finded :-)
		//Verify active user is admin
		array_push($usersArray, array("isAdministrator" =>  unserialize($_SESSION["User"]) -> administrator));
	}

	echo json_encode($usersArray);

	return;
}

if (isset($_POST["disableUser"])) {
	include_once ("../_model/users-show-m.php");

	disableUser($_POST["idUser"]);

	echo json_encode("User disabled");
	return;
}

if (isset($_POST["enableUser"])) {
	include_once ("../_model/users-show-m.php");

	enableUser($_POST["idUser"]);

	echo json_encode("User enabled");
	return;
}
?>