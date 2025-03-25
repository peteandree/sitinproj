<?php
include "admin_check.php"; // Protect page
include "routes.php"; // Include the centralized route file

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

// Fetch sit-ins by subject (purpose)
$sql = "SELECT purpose, COUNT(*) AS sitin_count FROM sit_in_records GROUP BY purpose";
$result = $conn->query($sql);
$sitins_by_subject = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sitins_by_subject[$row['purpose']] = $row['sitin_count'];
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js for the bar graph -->
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
        .bar-graph {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .bar-graph h3 {
            margin-bottom: 20px;
        }
        .download-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .download-buttons button {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .download-buttons button:hover {
            background: #34495e;
        }
    </style>
</head>
<body>
    <?php include "admin_sidebar.php"; ?>
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

    <!-- Content Area -->
    <div class="content">
        <h2>Reports</h2>
        <p>View and download sit-in reports.</p>

        <!-- Bar Graph -->
        <div class="bar-graph">
            <h3>Sit-ins by Subject</h3>
            <canvas id="sitInChart"></canvas>
        </div>

        <!-- Download Buttons -->
        <div class="download-buttons">
            <button onclick="downloadExcel()">Download Excel</button>
            <button onclick="downloadPDF()">Download PDF</button>
            <button onclick="downloadCSV()">Download CSV</button>
            <button onclick="printReport()">Print Report</button>
        </div>
    </div>

    <script>
        // Bar Graph Data
        const ctx = document.getElementById('sitInChart').getContext('2d');
        const sitInChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($sitins_by_subject)) ?>,
                datasets: [{
                    label: 'Number of Sit-ins',
                    data: <?= json_encode(array_values($sitins_by_subject)) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Set the y-axis intervals to 1
                        }
                    }
                }
            }
        });

        // Download Excel
        function downloadExcel() {
            window.location.href = 'export_excel.php';
        }

        // Download PDF
        function downloadPDF() {
            window.location.href = 'export_pdf.php';
        }

        // Download CSV
        function downloadCSV() {
            window.location.href = 'export_csv.php';
        }

        // Print Report
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>