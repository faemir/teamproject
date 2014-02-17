Passes in requested weeks, to check if there is a DB entry that
matches their requested week selection. It returns the week id
if it exists, else nothing.

Josh

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
	
	$sql = "SELECT weekid FROM WeekTable WHERE week1 = $wk1 AND week2 = $wk2 AND week3 = $wk3 AND week4 = $wk4 AND week5 = $wk5 AND week6 = $wk6 AND week7 = $wk7 AND week8 = $wk8 AND week9 = $wk9 AND week10 = $wk10 AND week11 = $wk11 AND week12 = $wk12 AND week13 = $wk13 AND week14 = $wk14 AND week15 = $wk15";

    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>