<?php



$sql="SELECT * FROM Rounds WHERE CURDATE() >= startdate AND CURDATE() <= enddate"; 


	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>