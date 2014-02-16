<?php
$username=$_GET['username'];
$sessionid=$_GET['sessionid'];
$sessionid=md5($sessionid);
$sql="SELECT * FROM UserTable WHERE username='$username' AND sessid = '$sessionid'";

	include 'DBquery.php';
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>
