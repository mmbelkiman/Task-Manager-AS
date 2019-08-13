<?php
/**
 * /_commons/_php/ValidateQueriesMc.php
 *
 *
 * Database access
 *
 * @package    _commons
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.1
 * @date       2014/07/09 : 13:40
 * Campinas-SP | Brazil
 */
class ValidateQueriesMc {

	public static $dbServer = "localhost";
	public static $dbUsername = "root";
	public static $dbPassword = "";
	public static $dbName = "dbAlundraSystem";

	/**
	 * Connection of database
	 * @return mixed
	 */
	static function DbConnection() {
		return new mysqli(ValidateQueriesMc::$dbServer, ValidateQueriesMc::$dbUsername, ValidateQueriesMc::$dbPassword, ValidateQueriesMc::$dbName);
	}

	/**
	 * Queries treatment
	 * @param string $outsideQuery = bla bla bla
	 * @param string $arrayParameters  = bla
	 * @return mixed
	 */
	static function StartQuery($outsideQuery, $arrayParameters = null) {
		$db = self::DbConnection();

		//Query without where clause
		if (empty($arrayParameters)) {
			$stmt = $db -> prepare($outsideQuery);
			$stmt -> execute();
			$result = $stmt -> get_result();
			$rows = array();

			//If insert query, treatment
			if (strpos(strtolower($outsideQuery), "insert") !== false) {
				$idRecent = mysqli_insert_id($db);
				//Close database
				$db -> close();
				$stmt -> close();
				if ($idRecent == 0 || $idRecent == null) {
					return true;
				}
				return $idRecent;
			}

			while ($row = $result -> fetch_assoc()) {
				$rows[] = $row;
			}

			if (sizeof($rows) == 0) {
				//If not found data
				return false;
			}
			//Free result set
			$result -> close();
			//Close database
			$db -> close();
			$stmt -> close();
			return $rows;
			//Query with where clause
		} else {
			//Verify if a valide arguments
			if (count($arrayParameters) != substr_count($outsideQuery, '?')) {
				//'Invalid arguments';
				return false;
			}

			$strArgumentsTypes = str_repeat('s', count($arrayParameters));
			$stmt = $db -> prepare($outsideQuery);
			call_user_func_array(array($stmt, "bind_param"), array_merge(array($strArgumentsTypes), self::refValues($arrayParameters)));
			$stmt -> execute();
			//Get id recent
			$idRecent = mysqli_insert_id($db);
			$result = $stmt -> get_result();
			//Close stmt
			$stmt -> close();

			//If insert query, treatment
			if (strpos(strtolower($outsideQuery), "insert") !== false) {

				//Close database
				$db -> close();
				if ($idRecent == 0 || $idRecent == null) {
					return true;
				}
				return $idRecent;
			}
			//If update query, treatment
			else if (strpos(strtolower($outsideQuery), "update") !== false) {
				//Close database
				$db -> close();
				return true;
			}
			//If delete query, treatment
			else if (strpos(strtolower($outsideQuery), "delete") !== false) {
				//Close database
				$db -> close();
				return true;
			}
			//If select query, treatment
			else if (strpos(strtolower($outsideQuery), "select") !== false) {
				$rows = array();
				while ($row = $result -> fetch_assoc()) {
					$rows[] = $row;
				}
				if (sizeof($rows) == 0) {
					//If not found data
					return false;
				}

				//Free result set
				$result -> close();
				//Close database
				$db -> close();
				return $rows;

			}
			//If type query not found
			else {
				//	die("Error in query: Type not found");
				return false;
			}
		}

		if (strlen(mysqli_error($db)) > 0) {
			//	die("Error in the result: " . mysqli_error($db));
			return false;
		}

		if (empty($rows)) {
			//	die("Error in the result: No value finded");
			return false;
		}
		//die("Error in the result: No value finded");
		return false;
	}

	/**
	 * Treatment arrays
	 * @param array $arr = {list of parameters}
	 * @return array $refs = {list with reference of parameters}
	 */
	static function refValues($arr) {
		if (strnatcmp(phpversion(), '5.3') >= 0)//Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach ($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}

}
?>