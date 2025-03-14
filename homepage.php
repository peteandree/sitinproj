<?php
session_start();

// Session expiration: Logout after 15 minutes of inactivity
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

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

// Fetch logged-in user data
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT firstname FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$firstname = htmlspecialchars($user['firstname']); // Sanitize output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sit-in Monitoring System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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
        .notification-popup {
            background: #27ae60;
            color: white;
            padding: 15px;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            right: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2><a href="homepage.php"><i class="fas fa-user"></i> Dashboard</a></h2>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="edit_profile.php"><i class="fas fa-edit"></i> Edit Profile</a>
        <a href="announcement.php"><i class="fas fa-bullhorn"></i> Announcement</a>
        <a href="sit_in_rules.php"><i class="fas fa-book"></i> Sit-in Rules</a>
        <a href="sit_in_history.php"><i class="fas fa-history"></i> Sit-in History</a>
        <a href="reservation.php"><i class="fas fa-calendar-check"></i> Reservation</a>
        <a href="remaining_sessions.php"><i class="fas fa-clock"></i> View Remaining Sessions</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Notification Popup -->
    <div id="notification" class="notification-popup">
        Welcome, <?php echo $firstname; ?>!
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let notification = document.getElementById("notification");
            notification.style.display = "block";
            setTimeout(() => {
                notification.style.display = "none";
            }, 3000);
        });
    </script>

</body>
</html>
