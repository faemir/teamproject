<?php

    $sql="SELECT Username, Password FROM userTable";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>