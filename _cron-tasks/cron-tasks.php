<?PHP
include_once "../_public-html/_commons/_php/ValidateQueriesMc.php";

//Remove tasks after X days
$daysToMaintain = 33;
$query = " DELETE FROM " .ValidateQueriesMc::$dbName .".tbTasks WHERE activeTask = 0 AND (datediff(NOW(), deletTime) >= ?) ";
$response = ValidateQueriesMc::StartQuery($query, array("$daysToMaintain"));

//Remove tasks comments after X days
$daysToMaintain = 33;
$query = " DELETE FROM " .ValidateQueriesMc::$dbName .".tbTasksComments WHERE activeComment = 0 AND (datediff(NOW(), deletTime) >= ?) ";
$response = ValidateQueriesMc::StartQuery($query, array("$daysToMaintain"));

//Remove tests comments after X days
$daysToMaintain = 33;
$query = " DELETE FROM " .ValidateQueriesMc::$dbName .".tbTestsComments WHERE activeComment = 0 AND (datediff(NOW(), deletTime) >= ?) ";
$response = ValidateQueriesMc::StartQuery($query, array("$daysToMaintain"));


?>