<?php
/**
 * /_public-html/_model/TestsCommentskMc.php
 *
 *
 * Class with task's comments data
 *
 * @package    _model
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/19 : 12:19
 * Campinas-SP | Brazil
 */
class TestsCommentskMc {
	//Public variables
	public $idComment;
	public $idTest;
	public $idUser;
	public $commentText;
	public $dateComment;
	public $activeComment;
	public $deletTime;
	public $completeName;

	//Construct
	function __construct($idComment, $idTest, $idUser, $commentText, $dateComment, $activeComment, $deletTime, $completeName) {
		$this -> idComment = $idComment;
		$this -> idTest = $idTest;
		$this -> idUser = $idUser;
		$this -> commentText = $commentText;
		$this -> dateComment = $dateComment;
		$this -> activeComment = $activeComment;
		$this -> deletTime = $deletTime;
		$this -> completeName = $completeName;
	}

}
?>