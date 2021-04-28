<?php
    namespace Validation;
    
    class Validate{
        private static $instances = [];

        protected function __construct() { }
    
        protected function __clone() { }
    
        public function __wakeup()
        {
            throw new \Exception("Cannot unserialize a singleton.");
        }

        public static function get(): Validate
        {
            $cls = static::class;
            if (!isset(self::$instances[$cls])) {
                self::$instances[$cls] = new static();
            }
            return self::$instances[$cls];
        }

        public static function fieldsValidate($FIELDS, $db, $data): array{
            $errors = array(
                "error" => array(
                    "code" => "",
                    "message" => "Validation error",
                    "errors" => array()
                )
            );
            // * validation
            foreach ($FIELDS as $value) {
                switch ($value) {
                    case 'first_name':
                        if($error = self::validateName($value, strval($data[$value]))){
                            array_push($errors['error']['errors'], $error);
                        }
                        break;
                    
                    case 'last_name':
                        if($error = self::validateName($value, strval($data[$value]))){
                            array_push($errors['error']['errors'], $error);
                        }
                        break;
        
                    case 'phone':
                        if(!((strlen($data[$value]) < 15 && strlen($data[$value]) > 9) && (is_numeric($data[$value])))){       
                            array_push($errors['error']['errors'], array("$value" => array("incorrect phone")));
                        }
                        if($result = $db::validatePhone($data[$value])){
                            array_push($errors['error']['errors'], $result);
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
            return $errors;
        }

        public static function validateName(string $key, string $name){
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
    
        // function validateDate($date, $format = 'Y-m-d H:i:s')//date validation
        // {
        //     $d = DateTime::createFromFormat($format, $date);
        //     return $d && $d->format($format) == $date;
        // }
    }

    
?>