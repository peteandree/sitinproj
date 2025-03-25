<?php
include "admin_check.php"; // Protect page
include "routes.php"; // Ensure routes are available

// Database connection details
$host = 'localhost'; // Database host
$dbname = 'db'; // Your database name
$username = 'root'; // Default username for XAMPP
$password = ''; // No password

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Get the student ID from the request
$idNo = $conn->real_escape_string($_GET['idNo']);

// Fetch student details
$sql = "SELECT idNo, firstName, lastName, remaining_sessions FROM users WHERE idNo = '$idNo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the student data
    $row = $result->fetch_assoc();
    echo json_encode([
        'idNo' => $row['idNo'],
        'firstName' => $row['firstName'],
        'lastName' => $row['lastName'],
        'remaining_sessions' => $row['remaining_sessions']
    ]);
} else {
    // If no student is found, return an error
    echo json_encode(['error' => 'Student not found']);
}

// Close the database connection
$conn->close();
?>