<?php
    
    class DataBase
    {
        private $port = 3306;
        private $database = 'module2'; // имя базы данных
        private $sql_user = 'root'; // имя пользователя
        private $host = 'localhost'; // адрес сервера 
        private $sql_password = 'root'; // пароль
        public $link;

        public function sql_con(){
            $this->$link = mysqli_connect('localhost', 'root', 'root', "module2") or die("Ошибка ".mysqli_error($link));
        }

        public function result($sql){
            return mysqli_query($this->$link, $sql);
        }

        public function close(){
            mysqli_close($this->$link);
        }

        public function fetch_array($res){
            return mysqli_fetch_array($res);
        }

        // public function fetch_array(){
        //     return mysqli_fetch_array($this->$link);
        // }
    }

?>