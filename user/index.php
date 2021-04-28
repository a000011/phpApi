<?
    header('Content-Type: application/json');
    http_response_code(200);

    require '../DataBase/DataBase.php';
    $db = DataBase\MySQL::get();

    $auth_header = getAuthorizationHeader();

    if (preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
        $APITOKEN = $matches[1];
        $result = mysqli_fetch_array($db::authUserByToken($APITOKEN));
        if(!empty($result)){
            echo json_encode(
                array(
                    "first_name"=>$result['first_name'],
                    "last_name"=>$result['last_name'],
                    "phone"=>$result['phone'],
                    "document_number"=>$result['document_number']
                )
            );
        }
        else{
            unAuth();
        }
    }
    else{
        unAuth();
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
?>