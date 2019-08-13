<?php
/**
 * /_public-html/_commons/_php/validate-login.php
 *
 *
 * Validate the login of user
 * @package    _commons/_php
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 12:30
 * Campinas-SP | Brazil
 */

if (isset($_SESSION["User"])) {
	if (strpos(getCurrentPageURL(), "login.php")) {
		header("Location: home.php");
	} else if (isset($_SESSION["CurrentPageUrl"])) {
		$mCurrentPageUrl = $_SESSION["CurrentPageUrl"];
		//Clear session "CurrentPageUrl"
		unset($_SESSION["CurrentPageUrl"]);
		header("Location: " . $mCurrentPageUrl);
	}
} else {
	//Current page url contains login.php
	if (strpos(getCurrentPageURL(), "login.php")) {
		// You already on the login page.
	} else {
		//Contains url of page user
		$_SESSION["CurrentPageUrl"] = getCurrentPageURL();
		header("Location: login.php");
	}

}
?>
