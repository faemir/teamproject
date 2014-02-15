<?php
	
	$editRequestId = $_GET["editrequestid"];
	$sql = "DELETE FROM RoomBooking WHERE requestid = $editRequestId";
	include "DBquery.php"

?>