<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        include("Booking_POST.php");
    }
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        include("Booking_GET.php");
    }
?>