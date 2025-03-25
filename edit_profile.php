<?php
session_start();

// Enable error reporting (for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
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
$stmt = $pdo->prepare("SELECT firstName, lastName, email, year_lvl, profile_pic FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'] ?? $user['firstName'];
    $lastName = $_POST['lastName'] ?? $user['lastName'];
    $email = $_POST['email'] ?? $user['email'];
    $year_lvl = $_POST['yearLevel'] ?? $user['year_lvl'];
    $profile_pic = $user['profile_pic']; // Keep existing picture

    // Handle file upload
    if (!empty($_FILES['profile_pic']['name'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create folder if it doesn't exist
    }

    $file_name = basename($_FILES["profile_pic"]["name"]);
    $file_path = $target_dir . time() . "_" . $file_name; // Prevent duplicate names
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $file_path)) { // FIXED TYPO HERE
            $profile_pic = $file_path; // Update profile picture path
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
    }
}


    // Update database
    try {
        $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, year_lvl = ?, profile_pic = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstName, $lastName, $email, $year_lvl, $profile_pic, $user_id]);

        $_SESSION['profile_updated'] = 'Your profile has been successfully updated!';
        header("Location: edit_profile.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating profile: " . $e->getMessage();
    }
}

// Success message
$successMessage = $_SESSION['profile_updated'] ?? '';
unset($_SESSION['profile_updated']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h2>Edit Profile</h2>
        <p>Update your profile details here.</p>

        <!-- Display Success Message -->
        <?php if ($successMessage): ?>
            <div class="success-message">
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <h3>Profile Details</h3>

                <!-- Profile Picture Section -->
                <div class="profile-picture">
                    <?php if (!empty($user['profile_pic'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <p>No profile picture uploaded.</p>
                    <?php endif; ?>
                </div>

                <label for="profile_pic">Upload Profile Picture</label>
                <input type="file" id="profile_pic" name="profile_pic">

                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>">

                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                <!-- Year Level Dropdown -->
                <label for="yearLevel">Year Level:</label>
                <select id="yearLevel" name="yearLevel">
                    <option value="1st Year" <?php echo $user['year_lvl'] == '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                    <option value="2nd Year" <?php echo $user['year_lvl'] == '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                    <option value="3rd Year" <?php echo $user['year_lvl'] == '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                    <option value="4th Year" <?php echo $user['year_lvl'] == '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                </select>

                <button type="submit">Update Profile</button>
            </div>
        </form>
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
            margin: 0 auto;
        }
        .card h3 {
            margin-bottom: 15px;
        }
        .card label {
            display: block;
            margin-bottom: 5px;
        }
        .card input, .card select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .card button {
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .card button:hover {
            background: #0056b3;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</body>
</html>
