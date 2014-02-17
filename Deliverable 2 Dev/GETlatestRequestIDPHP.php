When a new request is added this is used to find that most recently added 
request by limitting the return to one
	Josh
<?php
	$sql = "SELECT requestid FROM EntryRequestTable ";
	$sql.= "ORDER BY requestid DESC LIMIT 1;";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>