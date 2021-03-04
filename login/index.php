<?php
    include("../validation.php");

    require_once("../DataBase/sql_connect.php");
    $DB = new DataBase();

    $DB->sql_con();

    $sql = 'SELECT * FROM users WHERE phone="'.$user_phone.'" AND password="'.$user_password.'"';
    $result = $DB->result($sql);
    //ошибка подключения
    if ($result == false) {
        print("Произошла ошибка при выполнении запроса");
    } 

    //ошибка валидации
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
    }else{
        if(mysqli_fetch_array($result) == NULL){
            $status = 401;
            echo json_encode(
                array(
                    "error" => array(
                        "code" => $status,
                        "message" => "Unauthorized",
                        "errors" => array(
                            $user_phone =>  "phone or password incorrect"
                        )
                    )
                )
            );
    
        }
        else{
            $status = 200;
            $user_api_token = random_int(1000, 99999999);
            echo json_encode(
                array(
                    "data" => array(
                        "token" => $user_api_token
                    )
                )
            );
        }
    }
    // выполняем операции с базой данных
    // закрываем подключение
    $DB->close();
    http_response_code($status);

    // {
    //     "phone": "89001234567",
    //     "password": "paSSword"
    //  }
     
?>