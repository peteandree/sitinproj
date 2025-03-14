<?php
include 'db.php'; // Include the database connection
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Decrease the remaining_sessions by 1, ensuring it does not go below 0
    $stmt = $pdo->prepare("UPDATE users SET remaining_sessions = GREATEST(remaining_sessions - 1, 0) WHERE id = ?");
    $stmt->execute([$user_id]);

    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to login page after logout
header("Location: login.php");
exit();
