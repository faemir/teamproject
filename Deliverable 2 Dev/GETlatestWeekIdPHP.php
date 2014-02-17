This is used in addrequests to make sure the new entry is added to the correct 
week
		Callum & Josh
<?php
	$sql = "SELECT weekid FROM WeekTable ORDER BY weekid DESC LIMIT 1;";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>