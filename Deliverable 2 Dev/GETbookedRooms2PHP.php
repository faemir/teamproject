this script works for the page addrequests, it is used to grab booked rooms to
be displayed on the 'booking' timetable - ensuring that the user can't book
a confirmed booking
	Callum
<?php
	$roomID = $_GET["roomid"];
	
	$sql="SELECT roomid,day,period,duration,week1,week2,"
	$sql+="week3,week4,week5,week6,week7,week8,week9,week10,week11"
	$sql+=",week12,week13,week14,week15 FROM ConfirmedBooking"
	$sql+="INNER JOIN EntryRequestTable"
	$sql+="ON EntryRequestTable.requestid = ConfirmedBooking.requestid"
	$sql+="INNER JOIN WeekTable"
	$sql+="ON EntryRequestTable.weekid = WeekTable.weekid"
	$sql+="WHERE ConfirmedBooking.roomid='$roomID';";
	

	
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
    echo $JSON;

?>