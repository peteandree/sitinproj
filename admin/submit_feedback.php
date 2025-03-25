<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sit_in_id = intval($_POST['sit_in_id']);
    $feedback = trim($_POST['feedback']);

    if (empty($feedback)) {
        die("Feedback cannot be empty.");
    }

    // Database connection
    $host = 'localhost';
    $dbname = 'db';
    $username = 'root';
    $password = '';
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert feedback into the database
    $query = "UPDATE sit_in_records SET feedback = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $feedback, $sit_in_id, $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Failed to submit feedback.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: sit_in_history.php");
    exit();
}
?>