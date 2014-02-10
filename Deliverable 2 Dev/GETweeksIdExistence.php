<?php

    $week1=1; //$_GET["wk1"];
    $week2=1; //$_GET["wk2"];
    $week3=1; //$_GET["wk3"];
    $week4=1; //$_GET["wk4"];
    $week5=1; //$_GET["wk5"];
    $week6=1; //$_GET["wk6"];
    $week7=1; //$_GET["wk7"];
    $week8=1; //$_GET["wk8"];
    $week9=1; //$_GET["wk9"];
    $week10=1; //$_GET["wk10"];
    $week11=1; //$_GET["wk11"];
    $week12=1; //$_GET["wk12"];
    $week13=0; //$_GET["wk13"];
    $week14=0; //$_GET["wk14"];
    $week15=0; //$_GET["wk15"];
    
    $sql = "SELECT weekid FROM WeekTable WHERE week1 = $week1 AND week2 = $week2 AND ";
    $sql .= "week3 = $week3 AND week4 = $week4 AND week5 = $week5 AND week6 = $week6 AND ";
    $sql .= "week7 = $week7 AND week8 = $week8 AND week9 = $week9 AND week10 = $week10 AND ";
    $sql .= "week11 = $week11 AND week12 = $week12 AND week13 = $week13 AND week14 = $week14 AND ";
    $sql .= "week15 = $week15";
    
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>