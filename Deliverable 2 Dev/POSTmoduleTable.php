<?php
	$code= $_GET["newModuleCode"];
	$title= $_GET["newModuleTitle"];
	$dept= $_GET["newDepartmentID"];
	
	$sql="INSERT INTO ModuleTable VALUES ($code, $title, $dept)";
	include "DBquery.php";

?>