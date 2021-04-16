<?
    require_once("../DataBase/sql_connect.php");
    header('Content-Type: application/json');
  
    $DB = new DataBase();
    $DB->sql_con();

    $code = explode("/", $_GET['code'])[0];

    $bookingReques = "SELECT * FROM bookings WHERE CODE='".$code."'";
    $booking = mysqli_fetch_array($DB->result($bookingReques));

    $flightFrom = getFlightInfo($booking['flight_from'], $DB);
    $flightFrom['from'] =  getAirportInfo($flightFrom['from_id'], $DB);
    $flightFrom['to'] =  getAirportInfo($flightFrom['to_id'], $DB);

    $flightBack = getFlightInfo($booking['flight_back'], $DB);
    $flightBack['from'] =  getAirportInfo($flightBack['from_id'], $DB);
    $flightBack['to'] =  getAirportInfo($flightBack['to_id'], $DB);

    $passengersRequest = "SELECT * FROM passengers WHERE booking_id={$booking['id']}";
    $passengersResult =  $DB->result($passengersRequest);
    $passengers = [];
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
        return arrayParcing(mysqli_fetch_array($DB->result($flightRequest)));
    }

    function getAirportInfo($Id, $DB){
        $airportRequest = "SELECT * FROM airports WHERE id={$Id}";
        return arrayParcing(mysqli_fetch_array($DB->result($airportRequest)));
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