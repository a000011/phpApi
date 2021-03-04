<?php
    header('Content-Type: application/json');
    require_once("../DataBase/sql_connect.php");
    $DB = new DataBase();
    
    $DB->sql_con();

    $iserror=false;
    $errors=array();

    if(strlen($_GET["from"]) > 3 || strlen($_GET["from"]) < 1){
        $iserror = true;
        $errors = array_merge($errors, array("from" => "field 'from' is incorrect"));
    }

    if(strlen($_GET["to"]) > 3 || strlen($_GET["to"]) < 1){
        $iserror = true;
        $errors = array_merge($errors, array("to" => "field 'to' is incorrect"));
    }

    $from_arr = get_airport($DB, $_GET["from"]);
    $to_arr = get_airport($DB, $_GET["to"]);
    $result_from = array();
    $result_to = array();

    $sql =  "SELECT * FROM flights WHERE from_id = '".$from_arr["id"]."' AND to_id = '".$to_arr["id"]."'"; 
    $result = $DB->result($sql);

    

    while($row = mysqli_fetch_array($result)){//заполнение массива полетов в пункт назначения
        array_push($result_from, fill_big_arr($row, $from_arr, $to_arr));
    }   
    
    $sql =  "SELECT * FROM flights WHERE from_id = '".$to_arr["id"]."' AND to_id = '".$from_arr["id"]."'";
    $result = $DB->result($sql);

    while($row = mysqli_fetch_array($result)){//заполнение массива полетов из пункта назначения
        array_push($result_to, fill_big_arr($row, $to_arr, $from_arr));
    } 

    $flight = array(//возвращаемый массив
        "data" => array(  //считываются только первые строки
            "flights_to" => $result_from,
            "flights_back" => $result_to,           
        )
    );
    if($iserror){
        $status = 422;
        echo json_encode(
            array(
                "error" => array(
                    "code" => $status,
                    "message" => "Validation error",
                    "errors" => $errors 
                )
            )
        );
    }else{
        echo json_encode($flight);
    }
    $DB->close();

    function get_airport($DB, $item){//поиск аэропортап в бд
        $sql = 'SELECT * FROM airports WHERE iata="'.$item.'"';
        
        return mysqli_fetch_array($DB->result($sql));
    }

    function fill_arr($arr, $time){
        return array(//заполнение маленького массива
            "city" => $arr["city"],
            "airport" => $arr["name"],
            "iata" => $arr["iata"],
            "time" => $time
        );
    }

    function fill_big_arr($res, $from_arr, $to_arr){//заполнение большого массива
        return array(
            "flight_id" => $res["id"],
            "flight_code" => $res["flight_code"],
            "from" => fill_arr($from_arr, $res["time_from"]),
            "to" => fill_arr($to_arr, $res["time_to"]),
            "cost" => $res["cost"]
        );
    }    
?>