This is getting the entered preferences of the user from the account page.
The preferences are for default values while adding new requests.
The details are then used to update the preferences table for that user.
The user's details are stored once they log in.
- Nikk Williams
<?php
	$username = $_GET['username'];
	$per = $_GET["per"];
	$hour = $_GET["hour"];
	$start = $_GET["start"];
	$end = $_GET["end"];
	$location = $_GET["location"];
	
	$sql="UPDATE Preferences SET period=$per, hr24format=$hour, ";
	$sql.="defaultstartweek=$start, defaultendweek=$end, ";
	$sql.="defaultlocation='$location' WHERE username='$username'";
	include "DBquery.php";
?>