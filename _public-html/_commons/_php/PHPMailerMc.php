<?php
/**
 * /_commons/_php/PHPMailerMC.php
 *
 *
 * Control PHPMailer lib
 *
 * @package    _commons
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/12 : 19:46
 * Campinas-SP | Brazil
 */
class PHPMailerMc {

	/**
	 * Send e-mail
	 * @param array(string) $addressed = all e-mails will receive message
	 * @param string $message  = body of e-mail
	 * @param string $theme  = This e-mail about? (ex: Task Comment, Taks Delete, New User)
	 * @param string $theme  = Theme of e-mail
	 * @return string
	 */
	static function sendEmail($addressed, $message, $theme, $subject) {

		$footer = "<br/>--------------------";
		$footer .= "<br/>This is e-mail is send automatic by Alundra System";

		if (file_exists('_libs/_php/class.phpmailer.php'))
			require_once ('_libs/_php/class.phpmailer.php');
		else
			require_once ('../_libs/_php/class.phpmailer.php');

		$mail = new PHPMailer();

		$body = $message . $footer;
		$body = preg_replace('/\\\\/', '', $body);

		$mail -> IsSMTP();
		$mail -> Host = "smtp.gmail.com";
		$mail -> SMTPAuth = true;
		$mail -> Username = "alundrasystem@gmail.com";
		$mail -> Password = "noisquevoa";
		$mail -> Port = 587;
		$mail -> SMTPSecure = "tls";

		$mail -> From = "alundrasystem@gmail.com";
		$mail -> FromName = "AlundraSystem";
		$mail -> AddReplyTo("alundrasystem@gmail.com", "AlundraSystem");

		for ($count = 0; $count < count($addressed); $count++) {
			$mail -> AddAddress($addressed[$count]);
		}
		$mail -> IsHTML(true);
		$mail -> CharSet = "uft-8";
		$mail -> WordWrap = 70;

		$mail -> Subject = "[AS-" . $theme . "] " . $subject;
		$mail -> Body = $body;
		//$mail -> AltBody = "teste";

		$sendReturn = $mail -> Send();
		if ($sendReturn) {
			return 'Message has been sent.';
		} else {
			return 'error' . $mail -> ErrorInfo;
		}
	}

}
?>