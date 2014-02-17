This is returning the relevant weeks in relation to the id GET,
for the purpose of displaying the weeks on the view requests page.

Matt

<?php

	$id = $_GET["id"];
	$sql = "SELECT * FROM WeekTable WHERE weekid = $id";
	include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>