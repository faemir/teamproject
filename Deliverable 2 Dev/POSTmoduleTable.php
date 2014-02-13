<?php
	$code= $_GET["code"];
	$title= $_GET["title"];
	$dept= $_GET["dept"];
	$sql="INSERT INTO ModuleTable VALUES ('$code', '$title', '$dept');";
	include "DBquery.php";

?>