<?php

	$sql ="SELECT CURRENT_TIMESTAMP;" ;
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>