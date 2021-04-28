<?php
    require '../validation.php';
    require '../DataBase/DataBase.php';
    
    header('Content-Type: application/json');

    $postData = file_get_contents('php://input');
    if(empty($postData)){
        $data = $_POST;
    }
    else{
        $data = json_decode($postData, true);   
    }
    $FIELDS = array(
        "password"
    );
    $db = DataBase\MySQL::get();
    $vl = Validation\Validate::get();

    $errors = $vl::fieldsValidate($FIELDS, $db, $data);
    

    if(!is_numeric($data['phone']) || strlen($data['phone']) != 11){
        array_push($errors['error']['errors'], array("phone"=>array("phone is incorrect")));
    }


    if(empty($errors['error']['errors'])){
        $result = $db::authUser($data);
        $authResult = mysqli_fetch_array($result);
        if (empty($authResult)) {
            $status = 401;
            http_response_code($status);
            echo json_encode(
                array(
                    "error" => array(
                        "code" => $status,
                        "message" => "Unauthorized",
                        "errors" => array(
                            "phone" =>  array("phone or password incorrect")
                        )
                    )
                )
            );
        } 
        else{
            $status = 200;
            http_response_code($status);
            echo json_encode(
                array(
                    "data" => array(
                        "token" => $authResult['api_token']
                    )
                )
            );
        }
    }
    else{
        $status = 422;
        $errors['error']['code'] = $status;
        http_response_code($status);
        echo json_encode($errors);
    }
    
?>