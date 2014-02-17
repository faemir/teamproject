This page grabs a sql statement from the addrequests JS for
collecting into a rooms list.

Callum

<?php

    $sql=$_GET["sqlrooms"];
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>