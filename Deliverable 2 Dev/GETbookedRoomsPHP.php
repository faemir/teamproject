Pulls an SQL statement created in JavaScript from the previous page
This script pulls from the database a list of all 'booked rooms'
grabbing from the ConfirmedBookings table and WeeksTable and 
EntryRequestsTable.
			Callum
<?php
	
	$sql=$_GET["sql"];

	
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
    echo $JSON;

?>