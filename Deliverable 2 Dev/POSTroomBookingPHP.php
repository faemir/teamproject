This script adds a new entry to RoomBooking for the passed request id and room.
In webpage javascript this is called several times if one or more roombookings
are made for the same request.
<?php	
	$editBool = $_GET["editBool"];
	$requestid = $_GET["requestid"];
	$room = $_GET["room"];
	$modulecode = $_GET["modulecode"];
	$sql = "INSERT INTO RoomBooking (RequestID, RoomID, ModuleCode) VALUES ($requestid,'$room','$modulecode');";
	include "DBquery.php";
?>