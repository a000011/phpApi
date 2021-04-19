<?php
    include("../validation.php"); 
    require '../DataBase/DataBase.php';

    $db = DataBase\MySQL::get();

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
    }
    else{
        $status = 202;
    
    // выполняем операции с базой данных
        $sql = 'INSERT INTO users (first_name, last_name, phone, password, document_number, api_token, created_at, updated_at) VALUES ("'.$first_name.'","'.$last_name.'","'.$user_phone.'","'.$user_password.'","'.$document_number.'", "1232233","'.date("Y-m-d H:i:s").'","'.date("Y-m-d H:i:s").'")';
        $result = $db::result($sql);
    
        if ($result == false) {
            print("Произошла ошибка при выполнении запроса (Возможно уже занято)");
        } 
    } 
    http_response_code($status);
    // {
    //     "first_name": "wa",
    //     "last_name":"aw",
    //     "phone":"11232132132",
    //     "document_number":"0123456789",
    //     "password":"qwer1234"
    // }
?>

