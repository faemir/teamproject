Gets all the request details and room bookings associated
with a request that's going to be edited. 
		Josh
<?php

	$id = $_GET["id"];
	$sql = "SELECT * FROM EntryRequestTable JOIN RoomBooking ";
	$sql.= "WHERE EntryRequestTable.requestid = $id"; 
	$sql.= "AND RoomBooking.requestid = $id";
	include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>