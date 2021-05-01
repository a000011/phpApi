<?php
echo "asdasd";
    header('Content-Type: application/json');

    require '../DataBase/DataBase.php';

    $db = DataBase\MySQL::get();

    $code = explode("/", $_GET['code'])[0];

    $bookingReques = "SELECT * FROM bookings WHERE CODE='".$code."'";
    $booking = mysqli_fetch_array($db::result($bookingReques));

    $flightFrom = getFlightInfo($booking['flight_from'], $db);
    $flightFrom['from'] =  getAirportInfo($flightFrom['from_id'], $db);
    $flightFrom['to'] =  getAirportInfo($flightFrom['to_id'], $db);

    $flightBack = getFlightInfo($booking['flight_back'], $db);
    $flightBack['from'] =  getAirportInfo($flightBack['from_id'], $db);
    $flightBack['to'] =  getAirportInfo($flightBack['to_id'], $db);

    $passengersRequest = "SELECT * FROM passengers WHERE booking_id={$booking['id']}";
    $passengersResult =  $db::result($passengersRequest);
    $passengers = array();
    //var_dump(arrayParcing($flightFrom));
    while ($row = mysqli_fetch_assoc($passengersResult)) {
        array_push($passengers, $row);
    }
    
    echo json_encode(
        array(
            "data"=> array(
                "code" => $code,
                "cost" => "",
                "flights" => array(
                    $flightFrom,
                    $flightBack
                ),
                "passengers" => $passengers
            )
        )
    );

    function getFlightInfo($Id, $DB){
        $flightRequest = "SELECT * FROM flights WHERE id={$Id}";
        return arrayParcing(mysqli_fetch_array($DB::result($flightRequest)));
    }

    function getAirportInfo($Id, $DB){
        $airportRequest = "SELECT * FROM airports WHERE id={$Id}";
        return arrayParcing(mysqli_fetch_array($DB::result($airportRequest)));
    }

    function arrayParcing($array){
        $parcedArray = [];
        foreach(array_keys($array) as $key){
            if(!is_int($key)){
                $parcedArray[$key] = $array[$key];
            }
        }
        return $parcedArray;
    }
?>