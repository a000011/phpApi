<?php
    declare(strict_types = 1);  
    require '../validation.php'; 
    require '../DataBase/DataBase.php';

    $db = DataBase\MySQL::get();
    header('Content-Type: application/json');

    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);
    $status = 422;
    $FIELDS = array(
        "first_name",
        "last_name",
        "phone",
        "document_number",
        "password"
    );
    $errors = array(
        "error" => array(
            "code" => $status,
            "message" => "Validation error",
            "errors" => array()
        )
    );
    // * validation
    foreach ($FIELDS as $value) {
        switch ($value) {
            case 'first_name':
                if($error = validateName($value, strval($data[$value]))){
                    array_push($errors['error']['errors'], $error);
                }
                break;
            
            case 'last_name':
                if($error = validateName($value, strval($data[$value]))){
                    array_push($errors['error']['errors'], $error);
                }
                break;

            case 'phone':
                if(!((strlen($data[$value]) < 15 && strlen($data[$value]) > 9) && (is_numeric($data[$value])))){       
                    array_push($errors['error']['errors'], array("$value" => array("incorrect phone")));
                }
                $phoneRequest = "SELECT * FROM users WHERE phone=$data[$value]";
                if($result = $db::fetch_array($db::result($phoneRequest))){
                    array_push($errors['error']['errors'], array("$value" => array("$value is registed")));
                }                
                break;

            case 'document_number':
                if(!((strlen($data[$value]) == 10) && (is_numeric($data[$value])))){
                    array_push($errors['error']['errors'], array("$value" => array("incorrect document number")));
                }
                break;

            case 'password':
                if(strlen($data[$value]) > 40 || strlen($data[$value]) < 8){
                    array_push($errors['error']['errors'], array("$value" => array("incorrect $value")));
                }
                break;
        }
    }

    if(empty($errors['error']['errors'])){
        $status = 204;
        http_response_code($status);
        $result = $db::registerUser($data); 
    }
    else{
        http_response_code($status);
        echo json_encode($errors);
    }
    
    
?>

