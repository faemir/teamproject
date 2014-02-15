<?php
    $sql = "SELECT Username, Password, sessID FROM UserTable";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>
