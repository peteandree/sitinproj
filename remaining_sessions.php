<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Get the logged-in user's ID from session (trim spaces)
$user_id = (int) $_SESSION['user_id']; // Convert to integer

// Fetch the user details
$stmt = $pdo->prepare("SELECT idNo, COALESCE(remaining_session, 30) AS remaining_session FROM credentials WHERE idNo = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $remaining_sessions = "Error: User not found. Please check if your account exists.";
} else {
    $remaining_sessions = $user['remaining_session'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remaining Sessions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h2>Remaining Sessions</h2>
        <p>
            <?php 
            if (is_numeric($remaining_sessions)) {
                echo "You have <strong>$remaining_sessions</strong> sit-in sessions remaining.";
            } else {
                echo "<span style='color: red;'>$remaining_sessions</span>";
            }
            ?>
        </p>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
            background: #f4f4f4;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .card h2 {
            margin-bottom: 15px;
        }
        .card p {
            font-size: 16px;
            margin: 8px 0;
        }
        .notification-popup {
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
        }
    </style>
</body>
</html>
