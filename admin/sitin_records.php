<?php
include "admin_check.php"; // Protect page

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

// Fetch all sit-in records from the database
$sql = "SELECT sit_in_records.*, users.firstName, users.lastName 
        FROM sit_in_records 
        JOIN users ON sit_in_records.idNo = users.idNo 
        ORDER BY sit_in_records.date DESC";
$result = $conn->query($sql);

// Check if the query executed successfully
if ($result === false) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }
        table th {
            background: #2c3e50;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        table td {
            border-bottom: 1px solid #ddd;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="sitin_records.php"><i class="fas fa-file-alt"></i> Sit-in Records</a>
        <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="search_student.php"><i class="fas fa-search"></i> Search Student</a>
        <a href="admin_announcement.php"><i class="fas fa-bullhorn"></i> Announcements</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Welcome, Admin</h2>
        
        <!-- Table to Display Sit-in Records -->
        <h3>Sit-in Records</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>ID Number</th>
                    <th>Purpose</th>
                    <th>Lab</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Loop through each row of the result set
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['firstName']} {$row['lastName']}</td>
                                <td>{$row['idNo']}</td>
                                <td>{$row['purpose']}</td>
                                <td>{$row['lab']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['status']}</td>
                              </tr>";
                    }
                } else {
                    // If no rows are returned, display a message
                    echo "<tr><td colspan='7' class='no-data'>No sit-in records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Notification Popup -->
    <div class="notification-popup" id="notificationPopup">
        Sit-in recorded successfully!
    </div>

    <script>
        // JavaScript to show/hide notification popup
        function showNotification() {
            const popup = document.getElementById('notificationPopup');
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000); // Hide after 3 seconds
        }

        // Check if a notification should be shown
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('notify')) {
            showNotification();
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>