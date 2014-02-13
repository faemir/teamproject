<?php
	$id = $_GET['id'];
	$sql= 	"DELETE a.*, b.* 
			FROM EntryRequestTable a 
			RIGHT JOIN RoomBooking b 
			ON b.requestid = a.requestid 
			WHERE a.requestid = $id;";
	include "DBquery.php";
	//echo $sql;
?>