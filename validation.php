<?php
    if(isset($data['birth_date'])){
        if(!validateDate($data['birth_date'])){
            $iserror = true;
            $errors = array_merge($errors, array("birth_date" => "incorrect birth date"));
        }
    }
    if(isset($user_password)){
        //
        // проверка пароль
        //
        
    }    
    

    function generateRandomString(int $length = 5) {//u
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function validateName(string $key, string $name){
        $errors = array($key => array());
        $iserror = False;
        if(!empty($name)){
            if((strlen($name) < 2) || (strlen($name) > 30)){
                $iserror = True;
                array_push($errors[$key], "incorrect length");
            }
        }
        else{
            $iserror = True;
            array_push($errors[$key], "Field is empty");
        }
        if(!$iserror){
            return False;
        }
        else{
            return $errors;
        }
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')//date validation
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
?>