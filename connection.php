<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "cabreraconnect_db";

if(!$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname)){
    die("La conexion ha fallado". mysqli_connect_error());
}