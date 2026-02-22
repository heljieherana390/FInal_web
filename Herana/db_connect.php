<?php
// db_connect.php
$servername = "localhost";
$username = "root"; // Change if yours is different
$password = "";     // Change if yours is different
$dbname = "herana_pastry"; // Make sure this matches your actual DB name

$conn = new mysqli("localhost", "root", "", "herana_pastry");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
