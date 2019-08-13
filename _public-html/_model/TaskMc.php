<?php
/**
 * /_public-html/_model/TaskMc.php
 *
 *
 * Class with task's data
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 12:19
 * Campinas-SP | Brazil
 */
class TaskMc {
	//Public variables
	public $idTask;
	public $idJob;
	public $idTaskStatus;
	public $name;
	public $description;
	public $createDate;
	public $updateDate;
	public $idUserDevelop;
	public $idUserCreation;
	public $hoursPlanned;
	public $hoursReal;
	public $nameUserCreation;
	public $nameUserDevelop;
	public $jobName;
	public $statusName;

	//Construct
	function __construct($idTask, $idJob, $idTaskStatus, $name, $description, $createDate, $updateDate = "", $idUserDevelop = "", $idUserCreation, $hoursPlanned, $hoursReal = "00:00:00", $nameUserCreation = "", $nameUserDevelop = "", $jobName = "", $statusName = "") {
		$this -> idTask = $idTask;
		$this -> idJob = $idJob;
		$this -> idTaskStatus = $idTaskStatus;
		$this -> name = $name;
		$this -> description = $description;
		$this -> createDate = $createDate;
		$this -> updateDate = $updateDate;
		$this -> idUserDevelop = $idUserDevelop;
		$this -> idUserCreation = $idUserCreation;
		$this -> hoursPlanned = $hoursPlanned;
		$this -> hoursReal = $hoursReal;
		$this -> nameUserCreation = $nameUserCreation;
		$this -> nameUserDevelop = $nameUserDevelop;
		$this -> jobName = $jobName;
		$this -> statusName = $statusName;

	}

}
?>