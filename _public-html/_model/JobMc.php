<?php
/**
 * /_public-html/_model/JobMc.php
 *
 *
 * Class with jobs of user
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 17:15
 * Campinas-SP | Brazil
 */
class JobMc {
	//Public variables
	public $idUser;
	public $idJob;
	public $job;

	//Construct
	function __construct($idUser, $idJob, $job) {
		$this -> idUser = $idUser;
		$this -> idJob = $idJob;
		$this -> job = $job;
	}

}
?>