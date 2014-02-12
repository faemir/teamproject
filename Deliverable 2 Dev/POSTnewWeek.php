<?php
	
	$wk1 = $_GET["weeks1"];
	$wk2 = $_GET["weeks2"];
	$wk3 = $_GET["weeks3"];
	$wk4 = $_GET["weeks4"];
	$wk5 = $_GET["weeks5"];
	$wk6 = $_GET["weeks6"];
	$wk7 = $_GET["weeks7"];
	$wk8 = $_GET["weeks8"];
	$wk9 = $_GET["weeks9"];
	$wk10 = $_GET["weeks10"];
	$wk11 = $_GET["weeks11"];
	$wk12 = $_GET["weeks12"];
	$wk13 = $_GET["weeks13"];
	$wk14 = $_GET["weeks14"];
	$wk15 = $_GET["weeks15"];
	
	$sql = "INSERT INTO WeekTable(week1,week2,week3,week4,week5,week6,week7,week8,";
	$sql .= "week9,week10,week11,week12,week13,week14,week15) VALUES ($wk1,$wk2,$wk3,";
	$sql .= "$wk4,$wk5,$wk6,$wk7,$wk8,$wk9,$wk10,$wk11,$wk12,$wk13,$wk14,$wk15);";
    include "DBquery.php";

?>