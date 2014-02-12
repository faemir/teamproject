<?php

	$sql = "SELECT weekid FROM WeekTable ORDER BY weekid DESC LIMIT 1;";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>