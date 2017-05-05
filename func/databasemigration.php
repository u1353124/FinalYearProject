<?php

/**
 * Mark Greenbank - U1353124
 *
 * Functions to build database and upgrade through versions safely
**/

//
function checkSchemeVersion($version) {
	try {
		$db = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),Config::get('mysql/username'), Config::get('mysql/password'));
		$sql = "SELECT * from schemeversion";
		$query = $db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();

	} catch (PDOException $e) {
		echo $e->getMessage();
	}
}

?>