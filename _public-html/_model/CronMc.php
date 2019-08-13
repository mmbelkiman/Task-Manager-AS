<?php
/**
 * /_public-html/_model/CronMc.php
 *
 *
 * Class with cron infos
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 17:15
 * Campinas-SP | Brazil
 */
class CronMc {
	//Public variables
	public $idTask;
	public $nameTask;
	public $time;

	//Construct
	function __construct($idTask, $nameTask, $time) {
		$this -> idTask = $idTask;
		$this -> nameTask = $nameTask;
		$this -> time = $time;
	}
}
?>