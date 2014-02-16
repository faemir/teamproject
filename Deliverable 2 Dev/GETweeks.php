<?php

    $id = $_GET["id"];
	

	$sql="SELECT *
	FROM WeekTable
	WHERE weekid='$id';";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>