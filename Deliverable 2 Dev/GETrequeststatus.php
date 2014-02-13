<?php
	$id=$_GET['id'];
	
	$sql= "SELECT * FROM EntryRequestTable WHERE requestid = $id" ;
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;

?>