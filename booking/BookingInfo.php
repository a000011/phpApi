<?
    header('Content-Type: application/json');

    require '../DataBase/DataBase.php';

    $db = DataBase\MySQL::get();


    if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
        $data = json_decode(file_get_contents('php://input'), true);//Принимаю PATCH запрос
        $sql = 'SELECT passengers.id, passengers.place_from, passengers.place_back
        FROM bookings
        INNER JOIN passengers ON bookings.id=passengers.booking_id
        WHERE CODE="'.explode("/", $_GET['code'])[0].'"';
        $result = $db::result($sql);
        $is_error = false;   
        $updateRequest='';
        switch($data['type']){
            case 'from':
                while($row = $db::fetch_array($result)){
                    if($row['place_from'] == $data['seat']){
                        $is_error = true; 
                        echo "error";
                    }
                }
                if(!$is_error){
                    $updateRequest = 'UPDATE passengers
                    SET place_from="'.$data['seat'].'"
                    WHERE id='.$data['passenger'].'';
                }
                break;
    
            case 'back':
                while($row = $db::fetch_array($result)){
                    if($row['place_back'] == $data['seat']){
                        $is_error = true; 
                    }
                }
                if(!$is_error){
                    $updateRequest = 'UPDATE passengers
                    SET place_back="'.$data['seat'].'"
                    WHERE id='.$data['passenger'];
                }
                break;
    
            case 'from/back':
                while($row = $db::fetch_array($result)){
                    if($row['place_back'] == $data['seat'] || $row['place_from'] == $data['seat']){
                        $is_error = true; 
                    }
                }
                if(!$is_error){
                    $updateRequest = 'UPDATE passengers
                    SET place_from="'.$data['seat'].'", place_back="'.$data['seat'].'"
                    WHERE id='.$data['passenger'];
                }
                break;
        }
        if(!$is_error){
            $db::result($updateRequest);
            $sql = 'SELECT * FROM passengers WHERE id='.$data['passenger'];
            $result = arrayParcing($db::fetch_array($db::result($sql)));
            http_response_code(200);
            echo json_encode(
                array(
                    "data" => $result
                )
            );
        }
        else{
            http_response_code(422);
            echo json_encode(
                array(
                    "errors" => array(
                        "code"=> http_response_code(),
                        "message" => "Seat is occupied"
                    )
                )
            );
        }
    }


    function arrayParcing($array){
        //этот метод убирает ненужные ключи из массива
        $parcedArray = [];
        foreach(array_keys($array) as $key){
            if(!is_int($key)){
                $parcedArray[$key] = $array[$key];
            }
        }
        return $parcedArray;
    }
?>