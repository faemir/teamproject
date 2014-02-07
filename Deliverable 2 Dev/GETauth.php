<?php

    $sql="SELECT Username, Password FROM UserTable";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>