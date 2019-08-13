<?php
/**
 * /_public-html/_model/TasksCommentskMc.php
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
class TasksCommentskMc {
	//Public variables
	public $idComment;
	public $idTask;
	public $idUser;
	public $commentText;
	public $dateComment;
	public $activeComment;
	public $deletTime;
	public $completeName;

	//Construct
	function __construct($idComment, $idTask, $idUser, $commentText, $dateComment, $activeComment, $deletTime, $completeName) {
		$this -> idComment = $idComment;
		$this -> idTask = $idTask;
		$this -> idUser = $idUser;
		$this -> commentText = $commentText;
		$this -> dateComment = $dateComment;
		$this -> activeComment = $activeComment;
		$this -> deletTime = $deletTime;
		$this -> completeName = $completeName;
	}

}
?>