<?php
/**
 * /_public-html/_model/TestsMc.php
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
class TestsMc {
	//Public variables
	public $idTest;
	public $idTask;
	public $description;
	public $testDate;
	public $testDateReady;
	public $testerId1;
	public $testerName1;
	public $testerStatus1;
	public $testerId2;
	public $testerName2;
	public $testerStatus2;
	public $testerId3;
	public $testerName3;
	public $testerStatus3;
	public $testerId4;
	public $testerName4;
	public $testerStatus4;
	public $idTestStatus;
	public $idTaskStatus;

	//Construct
	function __construct($idTest, $idTask, $description, $testDate, $testDateReady, $testerId1 = null, $testerName1 = null, $testerId2 = null, $testerName2 = null, $testerId3 = null, $testerName3 = null, $testerId4 = null, $testerName4 = null, $testerStatus1, $testerStatus2, $testerStatus3, $testerStatus4, $idTestStatus, $idTaskStatus) {
		$this -> idTest = $idTest;
		$this -> idTask = $idTask;
		$this -> description = $description;
		$this -> testDate = $testDate;
		$this -> testDateReady = $testDateReady;
		$this -> testerId1 = $testerId1;
		$this -> testerId2 = $testerId2;
		$this -> testerId3 = $testerId3;
		$this -> testerId4 = $testerId4;
		$this -> testerName1 = $testerName1;
		$this -> testerName2 = $testerName2;
		$this -> testerName3 = $testerName3;
		$this -> testerName4 = $testerName4;
		$this -> testerStatus1 = $testerStatus1;
		$this -> testerStatus2 = $testerStatus2;
		$this -> testerStatus3 = $testerStatus3;
		$this -> testerStatus4 = $testerStatus4;
		$this -> idTestStatus = $idTestStatus;
		$this -> idTaskStatus = $idTaskStatus;
	}

}
?>