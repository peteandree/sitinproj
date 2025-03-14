<?php
// Database connection
$host = "localhost";
$dbname = "db"; // Your database name
$username = "root"; // Your DB username
$password = ""; // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$successMessage = ''; // Variable for success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $idNo = $_POST['idNo'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $course = $_POST['course'] ?? '';
    $yearLevel = $_POST['yearLevel'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['userName'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        try {
            // Check if email or username already exists
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR userName = :userName");
            $checkStmt->execute(['email' => $email, 'userName' => $username]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                echo "<script>alert('Email or Username already exists. Try another one.');</script>";
            } else {
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO users 
                    (idNo, lastName, firstName, middleName, course, year_lvl, email, userName, password) 
                    VALUES (:idNo, :lastName, :firstName, :middleName, :course, :year_lvl, :email, :userName, :password)");

                $stmt->execute([
                    'idNo' => $idNo,
                    'lastName' => $lastname,
                    'firstName' => $firstname,
                    'middleName' => $middlename,
                    'course' => $course,
                    'year_lvl' => $yearLevel,
                    'email' => $email,
                    'userName' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT) // Securely hash the password
                ]);

                $successMessage = "Registration successful!";
            }
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sit-in Monitoring System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            color: green;
            font-size: 16px;
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="ccs.jpg" alt="Logo" style="display:block;margin:auto;width:100px;" />
        </div>
        <h1 style="text-align:center;">Registration</h1>

        <!-- Display the success message -->
        <?php if ($successMessage): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form id="registerForm" method="POST" action="">
            <input type="text" id="idNo" name="idNo" placeholder="ID No." required />
            <input type="text" id="lastname" name="lastname" placeholder="Lastname" required />
            <input type="text" id="firstname" name="firstname" placeholder="Firstname" required />
            <input type="text" id="middlename" name="middlename" placeholder="Middlename" />

            <select id="course" name="course" required>
                <option value="">Select Course</option>
                <option value="Bachelor of Science in Electrical Engineering">BSEE</option>
                <option value="Bachelor of Science in Civil Engineering">BSCE</option>
                <option value="Bachelor of Science in Computer Engineering">BSCPE</option>
                <option value="Bachelor of Science in Electronics Studies">BSECE</option>
                <option value="Bachelor of Science in Industrial Engineering">BSIE</option>
                <option value="Bachelor of Science in Mechanical Engineering">BSME</option>
                <option value="Bachelor of Science in Hospitality Management">BSHM</option>
                <option value="Bachelor of Science in Information Technology">BSIT</option>
                <option value="Bachelor of Science in Computer Science">BSCS</option>
                <option value="Bachelor of Science in Criminology">BSCRIM</option>
            </select>

            <select id="yearLevel" name="yearLevel" required>
                <option value="">Select Year Level</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>

            <input type="email" id="email" name="email" placeholder="Email Address" required />
            <input type="text" id="userName" name="userName" placeholder="Username" required />
            <input type="password" id="password" name="password" placeholder="Password" required />
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required />

            <button type="submit">Register</button>
        </form>

        <p style="text-align:center;"><a href="login.php">Already have an account? Log in here</a></p>
    </div>
</body>
</html>
