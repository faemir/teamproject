This gets all rounds info for putting it into a table on the
view requests page.

Matt

<?php
	$sql="SELECT * FROM Rounds ORDER BY startdate";
	include "DBquery.php";
	$JSON = json_encode($res->fetchAll());
	echo $JSON;
?>