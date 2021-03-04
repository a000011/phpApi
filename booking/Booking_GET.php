<?php
    header('Content-Type: application/json');
    include("../sql_connect.php");
    include("../validation.php");

    $link = mysqli_connect($host, $sql_user, $sql_password, $database) 
    or die("Ошибка ".mysqli_error($link));

    
?>