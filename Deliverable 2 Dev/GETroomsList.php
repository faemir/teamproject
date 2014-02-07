<?php

    $sql=$_GET["sqlrooms"];;
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>