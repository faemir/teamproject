This page is for returning the status of the request specified in the GET.

This is used in GETdeleteRequests to properly delete depending on the
status of the request.

Josh

<?php
	$id=$_GET['id'];
	
	$sql= "SELECT * FROM EntryRequestTable WHERE requestid = $id" ;
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;

?>