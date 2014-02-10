<?php
	
	$roomsarray = $_GET["roomsarray"];
	$sql="SELECT day, period, duration, week1, week2, week3, week4, week5, week6, week7, week8, week9, week10, week11, week12, week13, week14, week15 FROM EntryRequestTable, RoomBooking, WeekTable WHERE RoomBooking.roomid='$roomvariable' AND RoomBooking.modulecode=EntryRequestTable.modulecode AND EntryRequestTable.weekid=WeekTable.weekid AND EntryRequestTable.requeststatus='confirmed';";
	
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
    echo $JSON;

?>