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
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}

// Get form data
$idNo = $conn->real_escape_string($_POST['idNo']);
$purpose = $conn->real_escape_string($_POST['purpose']);
$lab = $conn->real_escape_string($_POST['lab']);

// Insert data into the sit_in_records table
$sql = "INSERT INTO sit_in_records (idNo, purpose, lab) VALUES ('$idNo', '$purpose', '$lab')";
if ($conn->query($sql) === true) {
    // Decrement the remaining_sessions in the users table
    $updateSql = "UPDATE users SET remaining_sessions = remaining_sessions - 1 WHERE idNo = '$idNo'";
    if ($conn->query($updateSql) === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

// Close the database connection
$conn->close();
?>