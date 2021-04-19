<?php 
    namespace DataBase;

    class MySQL{

        private static $instances = [];
        private $port = 3306;
        private $database = 'module2'; // имя базы данных
        private $sql_user = 'root'; // имя пользователя
        private $host = 'localhost'; // адрес сервера 
        private $sql_password = 'root'; // пароль
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
                self::$link = mysqli_connect('localhost', 'root', 'root', "module2");
                self::$instances[$cls] = new static();
            }
            return self::$instances[$cls];
        }

        public static function test()
        {
            echo "hello";
        }

        public static function result($sql){
            return mysqli_query(self::$link, $sql);
        }

        public static function fetch_array($res){
            return mysqli_fetch_array($res);
        }
    }
?>