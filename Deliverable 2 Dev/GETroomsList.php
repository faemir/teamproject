<?php

    $sql="SELECT roomid, building, capacity FROM RoomDetails";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>