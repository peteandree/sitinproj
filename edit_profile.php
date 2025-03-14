<?php
session_start();

// Disable error reporting for production
error_reporting(0); // Disable all error reporting
ini_set('display_errors', 0); // Do not display errors

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

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use the null coalescing operator (??) to avoid undefined index notices
    $firstName = htmlspecialchars($_POST['firstName'] ?? ''); 
    $lastName = htmlspecialchars($_POST['lastName'] ?? ''); 
    $email = htmlspecialchars($_POST['email'] ?? '');
    $yearLevel = htmlspecialchars($_POST['yearLevel'] ?? ''); // Get year level input

    // Update the user's profile in the database, including the year level and email
    $sql = "UPDATE users SET email = ?, yearLevel = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $yearLevel, $user_id]);

    // Set success message in the session
    $_SESSION['profile_updated'] = 'Your profile has been successfully updated!';

    // Fetch the updated user data after the update
    $user['email'] = $email;
    $user['yearLevel'] = $yearLevel;

    // Redirect to refresh the page and reflect the updated data
    header("Location: edit_profile.php");
    exit();
} else {
    // Set the values for the form if not posted yet
    $firstName = $user['firstName'];
    $lastName = $user['lastName'];
    $email = $user['email'];
    $yearLevel = $user['yearLevel']; // Get the current year level
}

// Check for success message in the session
$successMessage = isset($_SESSION['profile_updated']) ? $_SESSION['profile_updated'] : '';
if ($successMessage) {
    unset($_SESSION['profile_updated']); // Clear the message after displaying it
}
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

        <!-- Display Success Message if Set -->
        <?php if ($successMessage): ?>
            <div class="success-message">
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form action="edit_profile.php" method="POST">
            <div class="card">
                <h3>Profile Details</h3>

                <!-- Input Fields for User Details (name fields are optional) -->
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" autocomplete="given-name">

                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" autocomplete="family-name">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="email">

                <!-- Year Level Dropdown -->
                <label for="yearLevel">Year Level:</label>
                <select id="yearLevel" name="yearLevel">
                    <option value="1st Year" <?php echo $yearLevel == '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                    <option value="2nd Year" <?php echo $yearLevel == '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                    <option value="3rd Year" <?php echo $yearLevel == '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                    <option value="4th Year" <?php echo $yearLevel == '4th Year' ? 'selected' : ''; ?>>4th Year</option>
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
            margin-left: 270px; /* Space for the sidebar */
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
            margin: 0 auto; /* Center the card horizontally */
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

        /* Success message styling */
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
