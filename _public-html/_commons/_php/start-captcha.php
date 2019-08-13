<?php
/**
 * /_public-html/_commons/start-captcha.php
 *
 *
 * Control that treatment of Google's captcha
 *
 * @package    _control
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/29 : 23:42
 * Campinas-SP | Brazil
 */
if (!isset($_POST["validateCaptcha"])) {
	require_once ("_libs/_php/recaptchalib.php");
	//local public key: 6LduA_YSAAAAAHxKLuHMKx7ZelfvyQClzvavsj2B
	//local private key: 6LduA_YSAAAAALWTzD-E9OemrDD5cUf7FRN2jsiw

	//Using the public key of site
	$publickey = "6LdddPYSAAAAABXYEfL6a5-VGh1T1M4ewSNBwKa1";

	//Show the captcha
	echo recaptcha_get_html($publickey);
}

if (isset($_POST["validateCaptcha"])) {
	require_once ('../../_libs/_php/recaptchalib.php');
	//Using the private key of site
	$privatekey = "6LdddPYSAAAAAJHkGTfPNw6vo_1WOC0TxGOF3y3Z";
	$usersArray = array();

	$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

	if (!$resp -> is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		array_push($usersArray, array("error" => "The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp -> error . ")"));
	} else {
		//Successful verification
		array_push($usersArray, array("validate" => "true"));
	}
	echo json_encode($usersArray);
	return;
}
?>