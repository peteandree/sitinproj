<?php
include 'db.php'; // Include the database connection
session_start();

// Database connection
$host = "localhost";
$dbname = "db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Decrement remaining sessions, ensuring it doesn't go below zero
    $stmt = $pdo->prepare("UPDATE users SET remaining_sessions = GREATEST(remaining_sessions - 1, 0) WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Destroy session and redirect to login page
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>