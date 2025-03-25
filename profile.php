<?php
$page_title = "Profile"; // Set the page title before including the header
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

// Ensure column names match your database table
$stmt = $pdo->prepare("SELECT id, firstName, lastName, course, year_lvl, profile_pic, COALESCE(remaining_sessions, 30) AS remaining_sessions FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found or no data returned.");
}

// Default profile picture if none is uploaded
$profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : "uploads/default_profile.png"; 
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style.css">

<div class="content">
    <div class="card">
        <h2>Profile</h2>

         <!-- Display Profile Picture -->
         <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
        </div>

        <div class="profile-details">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></p>
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
        padding: 100px;
        border-radius: 100px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        width: 1000%;
        max-width: 1000px;
        height: 500px;
        text-align: center;
    }
    .profile-picture {
        margin-bottom: 15px;
    }
    .profile-picture img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #2c3e50;
    }
    .profile-details {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    }

    .profile-details p {
    display: flex;
    gap: 5px; /* Keeps only one space between label and value */
    margin: 0;
    padding: 0;
}

.profile-details strong {
    font-weight: bold;
}
</style>

</div>
</body>
</html>
