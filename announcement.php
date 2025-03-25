<?php
$page_title = "Announcements";
include 'header.php';

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

// Fetch all published announcements from the database
$sql = "SELECT * FROM announcements WHERE status = 'Published' AND start_date <= NOW() AND end_date >= NOW() ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if the query executed successfully
if ($result === false) {
    die("Query failed: " . $conn->error);
}
?>

<div class="card">
    <link rel="stylesheet" href="style.css">

    <h2>Announcements</h2>

    <?php
    if ($result->num_rows > 0) {
        // Loop through each row of the result set
        while ($row = $result->fetch_assoc()) {
            echo "<div class='announcement-box'>
                    <h3>{$row['title']}</h3>
                    <p>{$row['content']}</p>
                    <small>Category: {$row['category']} | Posted on: {$row['created_at']}</small><br>
                    <small>Start Date: {$row['start_date']} | End Date: {$row['end_date']}</small>
                  </div>";
        }
    } else {
        // If no rows are returned, display a message
        echo "<p>No new announcements.</p>";
    }
    ?>
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
    .announcement-box {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    }
    .announcement-box h3 {
        margin-bottom: 10px;
    }
    .announcement-box p {
        margin-bottom: 10px;
    }
    .announcement-box small {
        color: #777;
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