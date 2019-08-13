<?php
if (!$_SESSION["PATH_SERVER"]) {
	$path = $_SERVER['SCRIPT_FILENAME'];
	$path_parts = pathinfo($path);
	
	$_SESSION["PATH_SERVER"] = $path_parts['dirname'];
}
?>