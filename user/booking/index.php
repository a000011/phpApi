<?
    header('Content-Type: application/json');
    http_response_code(200);

    require '../../DataBase/DataBase.php';
    $db = DataBase\MySQL::get();

    $auth_header = getAuthorizationHeader();

    if (preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
        $APITOKEN = $matches[1];
        if($booking = getBookingId($APITOKEN, $db)){
            echo json_encode(getFlights($booking['booking_id'], $db));
        }
        else{
            unAuth();
        }
    }
    else{
        unAuth();
    }
    
    function getFlights($bookingId, $DB){
        $bookingRequest = "SELECT * FROM bookings WHERE id=$bookingId";
        $booking = arrayParcing($DB::fetch_array($DB::result($bookingRequest)));
        $flightFrom = getFlightInfo($booking['flight_from'], $DB);
        $flightFrom['from'] =  getAirportInfo($flightFrom['from_id'], $DB);
        $flightFrom['to'] =  getAirportInfo($flightFrom['to_id'], $DB);

        $flightBack = getFlightInfo($booking['flight_back'], $DB);
        $flightBack['from'] =  getAirportInfo($flightBack['from_id'], $DB);
        $flightBack['to'] =  getAirportInfo($flightBack['to_id'], $DB);
        return array(
            "data"=> array(
                "code" => 200,
                "cost" => "",
                "flights" => array(
                    $flightFrom,
                    $flightBack
                )
            )
        );
    }

    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    function unAuth(){
        http_response_code(401);
        $error = array("error"=>array(
            "code"=>401, 
            "message"=> "Unauthorized"
        ));
        echo json_encode($error);
    }

    function getFlightInfo($Id, $DB){
        $flightRequest = "SELECT * FROM flights WHERE id={$Id}";
        return arrayParcing(mysqli_fetch_array($DB::result($flightRequest)));
    }
    function getBookingId($apiToken, $DB){
        $bookingRequest =  "
            SELECT passengers.booking_id
            FROM users
            INNER JOIN passengers ON users.document_number=passengers.document_number
            WHERE api_token='$apiToken'";
        return arrayParcing($DB::fetch_array($DB::result($bookingRequest)));
    }
    function getAirportInfo($Id, $DB){
        $airportRequest = "SELECT * FROM airports WHERE id={$Id}";
        return arrayParcing(mysqli_fetch_array($DB::result($airportRequest)));
    }

    function arrayParcing($array){
        $parcedArray = [];
        if(!empty($array)){
            foreach(array_keys($array) as $key){
                if(!is_int($key)){
                    $parcedArray[$key] = $array[$key];
                }
            }
        }
        return $parcedArray;
    }
?>