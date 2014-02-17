<?php
	$id = $_GET['id'];
	$status=$_GET['status'];
	if ($status == "0"){
	$sql= 	"DELETE a.*, b.* FROM EntryRequestTable a 
	RIGHT JOIN RoomBooking b ON b.requestid = a.requestid 
	WHERE a.requestid = $id;";
	}
	else if ($status == "1"){
	$sql= 	"DELETE a.*, b.*, c.* FROM EntryRequestTable a 
	RIGHT JOIN RoomBooking b ON b.requestid = a.requestid 
	RIGHT JOIN ConfirmedBooking c ON a.requestid = c.requestid 
	WHERE a.requestid = $id;";
	}
	include "DBquery.php";
	//echo $sql;
?>