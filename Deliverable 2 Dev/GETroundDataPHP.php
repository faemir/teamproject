This page gets the current date, comparing it to the start and end date
of each round.

Matt

<?php



$sql="SELECT * FROM Rounds WHERE CURDATE() >= startdate AND CURDATE() <= enddate"; 


	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>