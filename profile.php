<?php
include 'header.php';
include 'db.php'; // Include the database connection

// Start session only if it is not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user data
$user_id = $_SESSION['user_id'];

// Update column names based on your actual database structure
$stmt = $pdo->prepare("SELECT firstName, lastName, course, year_lvl, remaining_sessions FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style.css">

<div class="content">
    <div class="card">
        <h2>Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName'] . " " . $user['lastName']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></p>
        <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['year_lvl']); ?></p>
        <p><strong>Remaining Sessions:</strong> <?php echo htmlspecialchars($user['remaining_sessions']); ?></p>
    </div>
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
</style>
</div>
</body>
</html>
