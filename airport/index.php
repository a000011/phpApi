<?php
    header('Content-Type: application/json');

    require_once("../DataBase/sql_connect.php");
    $DB = new DataBase();
    
    $DB->sql_con();

    $items = array();
    $arr_moscow = array("oscow", "moscow", "Sheremetyevo", "Shere", "SVO");

    if(isset($_GET["name"])){
        $sql = 'SELECT city, name, iata FROM airports WHERE name="'.$_GET["name"].'"';
    }elseif (isset($_GET["city"])) {
        $sql = 'SELECT city, name, iata FROM airports WHERE city="'.$_GET["city"].'"';
    }elseif (isset($_GET["iata"])) {
        $sql = 'SELECT city, name, iata FROM airports WHERE iata="'.$_GET["iata"].'"';
    }


    if(array_search(strtolower($air_name), $arr_moscow) !== false){     
        $result = mysqli_fetch_array($DB->result($sql));         
    }
    else{
        $result = mysqli_fetch_array($DB->result($sql));         
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
    $DB->close();  
?>