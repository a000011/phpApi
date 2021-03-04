<?php
    header('Content-Type: application/json');
    
    $iserror = false;
    $errors = array();
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);
   
    $first_name = $data["first_name"];
    $last_name = $data['last_name'];
    $user_phone = $data['phone'];
    $document_number = $data['document_number'];
    $user_password = $data['password'];
    $birth_date = $data['birth_date'];

    if(isset($first_name)){
        //  
        // проверка имени
        //
        if(strlen($first_name) > 25 || strlen($first_name) < 2){
            $iserror = true;
            $errors = array_merge($errors, array("first_name" => "to long or to short first name"));
        }
    }        
    
    if(isset($data['birth_date'])){
        if(!validateDate($data['birth_date'])){
            $iserror = true;
            $errors = array_merge($errors, array("birth_date" => "incorrect birth date"));
        }
    }

    
    
    if(isset($data['last_name'])){
        //
        // проверка фамилии
        //        
        if(strlen($data['last_name']) > 25 || strlen($data['last_name']) < 2){
            $iserror = true;
            $errors = array_merge($errors, array("last_name" => "to long or to short last name"));
        }
    } 

    if(isset($data['user_phone'])){
        //
        // проверка телефона
        //
        if(!((strlen($data['user_phone']) < 25 && strlen($data['user_phone']) > 1) && (is_numeric($data['user_phone'])))){       
            $iserror = true;
            $errors = array_merge($errors, array("phone" => "incorrect phone"));      
        }
    }

    if(isset($document_number)){
        //
        // проверка номера документа
        //
        if(!((strlen($document_number) == 10) && (is_numeric($document_number)))){
            $iserror = true;
            $errors = array_merge($errors, array("document_number" => "incorrect document number"));
        }
    }

    if(isset($user_password)){
        //
        // проверка пароль
        //
        if(strlen($user_password) > 40 || strlen($user_password) < 8){
            $iserror = true;
            $errors = array_merge($errors, array("password" => "incorrect password"));
        }
    }    
    

    function generateRandomString($length = 5) {//u
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')//date validation
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
?>