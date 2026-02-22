<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "herana_pastry";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>