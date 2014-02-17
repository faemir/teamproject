This is sent a request id and uses it to find in the database the relevant
entries and delete them. This will change depending on whether the 
status of the request is accepted or not as it needs to delete from 
three tables if it is accepted.
		Matt
<?php
	$id = $_GET['id'];
	$status=$_GET['status'];
	if ($status == "0"){
	$sql= 	"DELETE a.*, b.* FROM EntryRequestTable a ";
	$sql .= "RIGHT JOIN RoomBooking b ON b.requestid = a.requestid ";
	$sql .= "WHERE a.requestid = $id;";
	}
	else if ($status == "1"){
	$sql= 	"DELETE a.*, b.*, c.* FROM EntryRequestTable a ";
	$sql .= "RIGHT JOIN RoomBooking b ON b.requestid = a.requestid "; 
	$sql .= "RIGHT JOIN ConfirmedBooking c ON a.requestid = c.requestid "; 
	$sql .= "WHERE a.requestid = $id;";
	}
	include "DBquery.php";
?>