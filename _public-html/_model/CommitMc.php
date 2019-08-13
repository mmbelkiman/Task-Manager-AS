<?php
/**
 * /_public-html/_model/CommitMc.php
 *
 *
 * Class with commits
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/07/09 : 16:00
 * Campinas-SP | Brazil
 */
class CommitMc {
	//Public variables
	public $idCommit;
	public $name;
	public $files;
	public $dateUpdate;

	//Construct
	function __construct($idCommit, $name, $files, $dateUpdate) {
		$this -> idCommit = $idCommit;
		$this -> name = $name;
		$this -> files = $files;
		$this -> dateUpdate = $dateUpdate;
	}

}
?>