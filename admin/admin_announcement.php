<?php
include "admin_check.php"; // Protect page
include "routes.php"; // Ensure routes are available

// Database connection details
$host = 'localhost'; // Database host
$dbname = 'db'; // Your database name
$username = 'root'; // Default username for XAMPP
$password = ''; // No password

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for creating announcements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_announcement'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $status = $conn->real_escape_string($_POST['status']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $end_date = $conn->real_escape_string($_POST['end_date']);

    // Insert the announcement into the database
    $sql = "INSERT INTO announcements (title, content, category, status, start_date, end_date) 
            VALUES ('$title', '$content', '$category', '$status', '$start_date', '$end_date')";
    if ($conn->query($sql)) {
        echo "<script>alert('Announcement created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating announcement: " . $conn->error . "');</script>";
    }
}

// Fetch all announcements from the database
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if the query executed successfully
if ($result === false) {
    die("Query failed: " . $conn->error); // Display the SQL error
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .announcement-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
        }
        .create-announcement-form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .create-announcement-form input,
        .create-announcement-form textarea,
        .create-announcement-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .create-announcement-form button {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .create-announcement-form button:hover {
            background: #34495e;
        }
    </style>
</head>
<body>

    <?php include "admin_sidebar.php"; ?> <!-- Correctly include sidebar -->
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="<?= $routes['admin_dashboard']; ?>"><i class="fas fa-home"></i> Dashboard</a>
        <a href="<?= $routes['sit_in_records']; ?>"><i class="fas fa-file-alt"></i> Sit-in Records</a>
        <a href="<?= $routes['reports']; ?>"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="<?= $routes['search_student']; ?>"><i class="fas fa-search"></i> Search Student</a>
        <a href="<?= $routes['admin_announcement']; ?>"><i class="fas fa-bullhorn"></i> Announcements</a>
        <a href="<?= $routes['logout']; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Announcements</h2>

        <!-- Form to Create Announcements -->
        <div class="create-announcement-form">
            <h3>Create New Announcement</h3>
            <form action="" method="POST">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" placeholder="Content" rows="5" required></textarea>
                <select name="category" required>
                    <option value="General">General</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Event">Event</option>
                    <option value="Update">Update</option>
                </select>
                <select name="status" required>
                    <option value="Draft">Draft</option>
                    <option value="Published">Published</option>
                    <option value="Archived">Archived</option>
                </select>
                <input type="datetime-local" name="start_date" required>
                <input type="datetime-local" name="end_date" required>
                <button type="submit" name="create_announcement">Create Announcement</button>
            </form>
        </div>

        <!-- List of Announcements -->
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='announcement-box'>
                        <h3>{$row['title']}</h3>
                        <p>{$row['content']}</p>
                        <small>Category: {$row['category']} | Status: {$row['status']}</small><br>
                        <small>Start Date: {$row['start_date']} | End Date: {$row['end_date']}</small>
                      </div>";
            }
        } else {
            // If no rows are returned, display a message
            echo "<p><i>No announcements found.</i></p>";
        }
        ?>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>