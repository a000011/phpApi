<?php
error_reporting(E_ALL); ini_set('display_errors', '1');
    if(!isset($_GET['code'])){
        //бронирование
        include("Booking.php");
    }
    else
    {
        if(explode("/", $_GET['code'])[1] == "seat"){
            //Выбор места в салоне
            include("BookingInfo.php");
        }
        else{
            //Информация о бронировании
            include("BookingList.php");
        }
    }
    
?>