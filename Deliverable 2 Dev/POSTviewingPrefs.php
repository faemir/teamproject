<?php

	$username = 'admin';
	$per = $_GET["per"];
	$hour = $_GET["hour"];
	$start = $_GET["start"];
	$end = $_GET["end"];
	$location = $_GET["location"];
	
	$sql="UPDATE Preferences SET period='$per', hr24format='$hour', defaultstartweek='$start', defaultendweek='$end', defaultlocation='$location' WHERE username='$username'";
	include "DBquery.php";

?>