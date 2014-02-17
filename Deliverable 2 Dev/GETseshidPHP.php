This page takes all data from the user table for means of the
authentication with user and session id

Dan, Matt

<?php
	$username = $_GET['username'];
	$sql = "SELECT * FROM UserTable;";
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>