This finds the headers set by the user in the account page.
It then updates the preferences table with the set values for column headers.
The username is stored on user login.
- Nikk Williams
<?php
	$username = $_GET['username'];
	$h1= $_GET["h1"];
	$h2= $_GET["h2"];
	$h3= $_GET["h3"];
	$h4= $_GET["h4"];
	$h5= $_GET["h5"];
	$h6= $_GET["h6"];
	
	$sql="UPDATE Preferences SET header1=$h1, header2=$h2, header3=$h3, ";
	$sql.="header4=$h4, header5=$h5, header6=$h6 WHERE username='$username'";
	include "DBquery.php";
?>