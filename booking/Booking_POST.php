<?php
    header('Content-Type: application/json');
    include("../validation.php");
    require_once("../DataBase/sql_connect.php");
    $DB = new DataBase();
    $DB->sql_con();
    
    $flight_from = $data["flight_from"];
    $flight_back = $data["flight_back"];
    $passengers = $data["passengers"];
    $date_from = $flight_from["date"];
    $date_back = $flight_back["date"];

    $sql = 'SELECT code, id FROM bookings WHERE flight_from='.$flight_from["id"].' AND flight_back='.$flight_back["id"].' AND date_from="'.$date_from.'" AND date_back="'.$date_back.'"';
    $result = mysqli_fetch_array($DB->result($sql));
    
    if($result == NULL){
        $iserror = true;
        $errors = array_merge($errors, array("booking" => "cant find your booking"));
    }else{
        foreach ($passengers as $p) {
            $now_date = date("Y-m-d H:i:s");
            $sql = 'INSERT INTO passengers(updated_at, booking_id, first_name, last_name, birth_date, document_number, created_at) VALUES ("'.date("Y-m-d H:i:s").'", "'.$result["id"].'", "'.$p["first_name"].'", "'.$p["last_name"].'", "'.$p["birth_date"].'", "'.$p["document_number"].'", "'.date("Y-m-d H:i:s").'")';
            $result_insert=$DB->result($sql);
        }
    }

    if($iserror){
        echo json_encode(
            array(
                "error" => array(
                    "code" => "422",
                    "message" => "Validation error",
                    "errors" => $errors 
                )
            )
        );
        
    }else{
        echo json_encode(
            array(
                "data"=> array(
                    "code" => $result["code"]
                )
            )
        );
    }
    $DB->close();

    

    // {
    //     "flight_from": {
    //         "id": 1,
    //         "date": "2020-09-20"
    //     },
    //     "flight_back": {
    //         "id": 2,
    //         "date": "2020-09-30"
    //     },
    //     "passengers": [
    //         {
    //             "first_name": "Ivan",
    //             "last_name": "Ivanov",
    //             "birth_date": "1990-02-20",
    //             "document_number": "1234567890"
    //         },
    //         {
    //             "first_name": "Ivan",
    //             "last_name": "Gorbunov",
    //             "birth_date": "1990-03-20",
    //             "document_number": "1224567890"
    //         }
    //     ]
    // }
    
?>

