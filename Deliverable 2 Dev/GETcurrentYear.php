<?php

	$sql = "SELECT * FROM CurrentYear;";
	include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>