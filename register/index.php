<?php
    declare(strict_types = 1);  

    require '../validation.php'; 
    require '../DataBase/DataBase.php';

    $vl = Validation\Validate::get();
    $db = DataBase\MySQL::get();
    header('Content-Type: application/json');
    $postData = file_get_contents('php://input');
    if(empty($postData)){
        $data = $_POST;
    }
    else{
        $data = json_decode($postData, true);   
    } 
    $status = 422;
    $FIELDS = array(
        "first_name",
        "last_name",
        "phone",
        "document_number",
        "password"
    );
    $errors = $vl::fieldsValidate($FIELDS, $db, $data);
    if(empty($errors['error']['errors'])){
        $status = 204;
        http_response_code($status);
        $result = $db::registerUser($data); 
    }
    else{
        $errors['error']['code'] = $status;
        http_response_code($status);
        echo json_encode($errors);
    }
?>

