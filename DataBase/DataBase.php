<?php 
    namespace DataBase;
    
    require dirname(__FILE__).'/../Configuration.php';
    use Configuration;  
    
    class MySQL{

        private static $instances = [];
        private static $link;

        protected function __construct() { }
    
        protected function __clone() { }
    
        public function __wakeup()
        {
            throw new \Exception("Cannot unserialize a singleton.");
        }

        public static function get(): MySQL
        {
            $cls = static::class;
            if (!isset(self::$instances[$cls])) {
                self::$link = mysqli_connect(
                    Configuration\Config::$host,
                    Configuration\Config::$sql_user,
                    Configuration\Config::$sql_password,
                    Configuration\Config::$database
                );
                self::$instances[$cls] = new static();
            }
            return self::$instances[$cls];
        }

        public static function result($sql){
            //var_dump( mysqli_query(self::$link, $sql));
            return mysqli_query(self::$link, $sql);
        }

        public static function fetch_array($res){
            return mysqli_fetch_array($res);
        }

        public static function validatePhone($value){
            $phoneRequest = "SELECT * FROM users WHERE phone=$value";
            if($result = self::fetch_array(self::result($phoneRequest))){
                return array("phone" => array("phone is registed"));
            }
            else{
                return False;
            }
        }

        public static function registerUser($data){
            $date = date("Y-m-d H:i:s");
            $first_name = $data["first_name"];
            $last_name = $data['last_name'];
            $user_phone = $data['phone'];
            $document_number = $data['document_number'];
            $user_password = $data['password'];
            $sql = "INSERT INTO users (first_name, last_name, phone, password, document_number, api_token, created_at, updated_at)
            VALUES ('$first_name','$last_name','$user_phone','$user_password','$document_number', '1232233','$date','$date')";
            return self::result($sql);
        }

        public static function authUser($data){
            $sql = 'SELECT * FROM users WHERE phone="'.$data['phone'].'" AND password="'.$data['password'].'"';
            return self::result($sql);    
        }

        public static function authUserByToken($token){
            $sql = "SELECT * FROM users WHERE api_token='$token'";
            return self::result($sql);    
        }
    }
?>