This is used in the editing of the modules. This script will delete all
room bookings from the database associated with the request being edited
so that a new room bookings can be written.
		Josh
<?php
	
	$editRequestId = $_GET["editrequestid"];
	$sql = "DELETE FROM RoomBooking WHERE requestid = $editRequestId";
	include "DBquery.php"

?>