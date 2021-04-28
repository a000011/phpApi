<?php
    header('Content-Type: application/json');
    require '../DataBase/DataBase.php';

    $db = DataBase\MySQL::get();

    $items = array();
    $arr_moscow = array("oscow", "moscow", "Sheremetyevo", "Shere", "SVO");

    if(isset($_GET["query"])){
        $sql = 'SELECT city, name, iata FROM airports WHERE iata="'.$_GET["query"].'"';
    }
        // else if (isset($_GET["city"])) {
        //     $sql = 'SELECT city, name, iata FROM airports WHERE city="'.$_GET["city"].'"';
        // }
        // else if (isset($_GET["iata"])) {
        //     $sql = 'SELECT city, name, iata FROM airports WHERE iata="'.$_GET["iata"].'"';
        // }


    if(array_search(strtolower($air_name), $arr_moscow) !== false){     
        $result = mysqli_fetch_array($db::result($sql));         
    }
    else{
        $result = mysqli_fetch_array($db::result($sql));         
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
?>