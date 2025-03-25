<?php
$servername = "localhost"; // Change if using a different host
$username = "root"; // Change if using a different user
$password = ""; // XAMPP default has no password
$database = "db"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
