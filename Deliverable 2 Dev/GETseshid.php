<?php
	$username = $_GET['username'];
	$sql = "SELECT * FROM UserTable;";
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>