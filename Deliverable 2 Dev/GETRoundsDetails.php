<?php
	$sql="SELECT * FROM Rounds ORDER BY startdate";
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>