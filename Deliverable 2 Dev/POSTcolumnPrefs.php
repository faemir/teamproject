<?php

	$username = 'admin';
	$h1= $_GET["h1"];
	$h2= $_GET["h2"];
	$h3= $_GET["h3"];
	$h4= $_GET["h4"];
	$h5= $_GET["h5"];
	$h6= $_GET["h6"];
	
	$sql="UPDATE Preferences SET header1=$h1, header2=$h2, header3=$h3, header4=$h4, header5=$h5, header6=$h6 WHERE username='$username'";
	include "DBquery.php";

?>