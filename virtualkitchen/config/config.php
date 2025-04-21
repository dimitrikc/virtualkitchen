<?php
$servername = "localhost";

// Check if running on Webmin by checking the host name or environment
if (strpos($_SERVER['HTTP_HOST'], 'cs2410-web01pvm.aston.ac.uk') !== false) {
    // Webmin server credentials
    $username = "u-240124715";
    $password = "pp96HehwNOx5dbw";
    $dbname   = "u_240124715_v-kitchen";
} else {
    // Local XAMPP credentials
    $username = "root";
    $password = "";
    $dbname   = "vkitchen";
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
