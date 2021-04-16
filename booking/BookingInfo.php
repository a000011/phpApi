<?
    //Допустим в самолете 100 мест: A1 - A20 B... C... D... E...    ,
    //тогда

    header('Content-Type: application/json');
    require_once("../DataBase/sql_connect.php");
    $DB = new DataBase();
    $DB->sql_con();

    echo(file_get_contents('php://input'));//Принимаю PATCH запрос

    function arrayParcing($array){
        //этот метод убирает ненужные ключи из массива
        $parcedArray = [];
        foreach(array_keys($array) as $key){
            if(!is_int($key)){
                $parcedArray[$key] = $array[$key];
            }
        }
        return $parcedArray;
    }
?>