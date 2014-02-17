<?php
	$roomID = $_GET["roomid"];
	
	$sql="SELECT roomid,day,period,duration,week1,week2,week3,week4,week5,week6,week7,week8,week9,week10,week11,week12,week13,week14,week15 FROM ConfirmedBooking
		INNER JOIN EntryRequestTable
		ON EntryRequestTable.requestid = ConfirmedBooking.requestid
		INNER JOIN WeekTable
		ON EntryRequestTable.weekid = WeekTable.weekid
		WHERE ConfirmedBooking.roomid='$roomID';";
	

	
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
    echo $JSON;

?>