<?php
    if(!isset($_GET['code'])){
        //бронирование
        include("Booking_POST.php");
    }else{
        if(explode("/", $_GET['code'])[1] == "seat"){
            //Выбор места в салоне
        }
        else{
            //Информация о бронировании
        }
    }
    
?>