<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "vehicle_service_system";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn){
    die("Database Connection Failed");
}

?>