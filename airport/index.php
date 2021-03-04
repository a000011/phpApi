<?php
    include("../sql_connect.php");
    header('Content-Type: application/json');

    $items = array();
    $arr_moscow = array("oscow", "moscow", "Sheremetyevo", "Shere", "SVO");

    $link = mysqli_connect($host, $sql_user, $sql_password, $database) 
    or die("Ошибка ".mysqli_error($link));

    if(isset($_GET["name"])){
        $sql = 'SELECT city, name, iata FROM airports WHERE name="'.$_GET["name"].'"';
    }elseif (isset($_GET["city"])) {
        $sql = 'SELECT city, name, iata FROM airports WHERE city="'.$_GET["city"].'"';
    }elseif (isset($_GET["iata"])) {
        $sql = 'SELECT city, name, iata FROM airports WHERE iata="'.$_GET["iata"].'"';
    }


    if(array_search(strtolower($air_name), $arr_moscow) !== false){     
        $result = mysqli_fetch_array(mysqli_query($link, $sql));         
    }
    else{
        $result = mysqli_fetch_array(mysqli_query($link, $sql));         
    }
   
    if($result !== NULL){
       $items = array("name" => $result["name"], "iata" => "$result[iata]");

    }

    echo json_encode(
        array(
            "data" => array(
                "items" => $items
            )
        )
    );
    mysqli_close($link);   
?>