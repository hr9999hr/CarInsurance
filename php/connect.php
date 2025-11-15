<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safedrive_insurance"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
global $conn;
?>