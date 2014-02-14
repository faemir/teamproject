<?php
	
	$sql=$_GET["sql"];

	
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
    echo $JSON;

?>