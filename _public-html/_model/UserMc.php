<?php
/**
 * /_public-html/_model/UserMc.php
 *
 *
 * Class with user's data
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 12:19
 * Campinas-SP | Brazil
 */
class UserMc {
	//Public variables
	public $idUser;
	public $jobs;
	public $name;
	public $lastName;
	public $email;
	public $photo;
	public $username;
	public $secretQuestion;
	public $activeUser;
	public $administrator;

	//Construct
	function __construct($idUser, $jobs, $name, $lastName, $email, $photo, $username, $secretQuestion, $activeUser, $administrator) {
		$this -> idUser = $idUser;
		$this -> jobs = $jobs;
		$this -> name = $name;
		$this -> lastName = $lastName;
		$this -> email = $email;
		$this -> photo = $photo;
		$this -> username = $username;
		$this -> secretQuestion = $secretQuestion;
		$this -> activeUser = $activeUser;
		$this -> administrator = $administrator;
	}

}
?>