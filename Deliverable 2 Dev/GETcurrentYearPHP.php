Gets the current university year which is stored on the database, this 
is instead of using the current year which obviously does not take into 
account the September to September year.
			Josh
<?php

	$sql = "SELECT * FROM CurrentYear;";
	include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>