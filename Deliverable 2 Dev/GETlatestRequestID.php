<?php
	$sql = "SELECT requestid FROM EntryRequestTable ORDER BY requestid DESC LIMIT 1;";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>