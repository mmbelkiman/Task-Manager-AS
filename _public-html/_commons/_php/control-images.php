<?php
if (isset($_GET['updateDatabase'])) {
	if (!isset($_SESSION))
		session_start();

	include_once $_SESSION["PATH_SERVER"] . "/_model/UserMc.php";

	$idUserLoged =  unserialize($_SESSION["User"]) -> idUser;
	$mReturn = array();

	if (isset($_POST['idUser'])) {
		if ($_POST['idUser'] == -1)
			$idUser = $idUserLoged;
		else
			$idUser = $_POST['idUser'];
	}

	$userDir = $_SESSION["PATH_SERVER"] . "/_images/_" . $idUser . "/";

	switch ($_POST["filename"]) {
		case 'profile' :
			include_once $_SESSION["PATH_SERVER"] . "/_model/users-registration-m.php";
			updatePhotoProfile($_POST['filenameFull'], $idUser);

			array_push($mReturn, array("return" => "Profile photo saved"));
			break;

		default :
			array_push($mReturn, array("return" => "No file selected"));
			break;
	}

	echo json_encode($mReturn);
	return;
}
if (isset($_GET['saveTemp'])) {
	session_start();
	include_once ("../../_model/UserMc.php");
	$uploaddir = '../../_images/_temp/';

	$error = false;
	$files = array();
	$mReturn = array();
	$idUser =     unserialize($_SESSION["User"]) -> idUser;

	foreach ($_FILES as $file) {

		//Resize image
		$fileName = $file['name'];
		$fileName = strtolower($fileName);
		$img_format = array('jpg', 'jpeg', 'png');
		$get_format = explode('.', $fileName);
		$name = $get_format[0];
		$ext = $get_format[1];
		$realName = $idUser . "." . $ext;

		if (in_array($ext, $img_format)) {

			if (move_uploaded_file($file['tmp_name'], $uploaddir . $realName)) {

				switch ($ext) {
					case 'png' :
						createPngResized($uploaddir, $realName);
						break;
					case 'jpeg' :
						createJpgResized($uploaddir, $realName);
						break;

					case 'jpg' :
						createJpgResized($uploaddir, $realName);
						break;
				}

				array_push($mReturn, array("files" => $realName));
			} else {
				array_push($mReturn, array("error" => "There was an error uploading your files"));
			}
		} else {
			array_push($mReturn, array("error" => "File format is invalid."));
		}
	}

	echo json_encode($mReturn);
	return;
}

if (isset($_GET['saveDir'])) {
	session_start();
	include_once ("../../_model/UserMc.php");

	$idUserLoged =     unserialize($_SESSION["User"]) -> idUser;
	$mReturn = array();

	if (isset($_POST['idUser'])) {
		if ($_POST['idUser'] == -1)
			$idUser = $idUserLoged;
		else
			$idUser = $_POST['idUser'];
	}

	$uploadDir = "../../_images/_temp/";
	$userDir = "../../_images/_" . $idUser . "/";

	//verify last image file is submited
	$listFiles = scandir($uploadDir, 1);
	$lastFileName = "";
	$lastFileDate = 0;
	foreach ($listFiles as $file) {
		if (trim($file) != "." && trim($file) != "..") {
			if ($lastFileDate < filemtime($uploadDir . $file)) {
				$mFile = explode('.', trim($file));
				$mFile = $mFile[0];
				if ($mFile == $idUserLoged) {
					$lastFileName = $file;
					$lastFileDate = filemtime($uploadDir . $file);
				}
			}
		}
	}
	//no files uploaded
	if ($lastFileName == "") {
		array_push($mReturn, array("error" => true));
		array_push($mReturn, array("return" => "No file selected"));
	} else {

		if (!file_exists($userDir)) {
			mkdir($userDir, 0777);
		}

		$get_format = explode('.', $lastFileName);
		$ext = $get_format[1];

		rename($uploadDir . $lastFileName, $userDir . $_POST["filename"] . "." . $ext);

		//array_push($mReturn, array("return" => "File saved in: " .$userDir . $_POST["filename"] . "." . $ext));
		array_push($mReturn, array("filename" => $_POST["filename"] . "." . $ext));

	}
	echo json_encode($mReturn);
	return;
}

function createJpgResized($uploaddir, $fileName) {
	$originalImage = imagecreatefromjpeg($uploaddir . $fileName);

	$imageSizeOriginal = getimagesize($uploaddir . $fileName);
	$originalWidth = $imageSizeOriginal[0];
	$originalHeight = $imageSizeOriginal[1];

	$newWidth = 1024;
	$newHeight = ($newWidth * $originalHeight) / $originalWidth;
	$resizedImage = imagecreatetruecolor(1024, 600);

	$offsetHeight = 0;

	if (($newHeight - 600) > 0) {
		$offsetHeight = ($newHeight - 600) / 10;
	}

	imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, $offsetHeight, $newWidth, $newHeight, $originalWidth, $originalHeight);
	imagejpeg($resizedImage, $uploaddir . $fileName);
	imagedestroy($resizedImage);
	imagedestroy($originalImage);
}

function createPngResized($uploaddir, $fileName) {
	$originalImage = imagecreatefrompng($uploaddir . $fileName);

	$imageSizeOriginal = getimagesize($uploaddir . $fileName);
	$originalWidth = $imageSizeOriginal[0];
	$originalHeight = $imageSizeOriginal[1];

	$newWidth = 1024;
	$newHeight = ($newWidth * $originalHeight) / $originalWidth;
	$resizedImage = imagecreatetruecolor(1024, 600);

	$offsetHeight = 0;

	if (($newHeight - 600) > 0) {
		$offsetHeight = ($newHeight - 600) / 10;
	}

	imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, $offsetHeight, $newWidth, $newHeight, $originalWidth, $originalHeight);
	imagepng($resizedImage, $uploaddir . $fileName);
	imagedestroy($resizedImage);
	imagedestroy($originalImage);
}
?>