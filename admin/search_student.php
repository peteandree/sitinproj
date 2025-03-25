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

// Function to fetch ENUM values for the `lab` field
function getLabEnumValues($conn) {
    $sql = "SHOW COLUMNS FROM sit_in_records LIKE 'lab'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $type = $row['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enumValues = explode("','", $matches[1]);
        return $enumValues;
    } else {
        return [];
    }
}

// Fetch ENUM values for the `lab` field
$labEnumValues = getLabEnumValues($conn);

// Initialize search term
$searchTerm = "";
if (isset($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']); // Sanitize input
}

// Query to fetch data from the `users` table based on search term
$sql = "SELECT idNo, firstName, lastName, middleName, course, year_lvl, remaining_sessions FROM users 
        WHERE idNo LIKE '%$searchTerm%' 
        OR firstName LIKE '%$searchTerm%' 
        OR lastName LIKE '%$searchTerm%' 
        OR middleName LIKE '%$searchTerm%' 
        OR course LIKE '%$searchTerm%' 
        OR year_lvl LIKE '%$searchTerm%' 
        OR remaining_sessions LIKE '%$searchTerm%'";
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
    <title>Students</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Existing CSS styles */
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
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background: #34495e;
        }
        .search-box .refresh-button {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .search-box .refresh-button:hover {
            background: #34495e;
        }

        /* Style for the Sit-in button */
        .sit-in-button {
            padding: 10px 20px;
            background: #28a745; /* Green color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .sit-in-button:hover {
            background: #218838; /* Darker green on hover */
        }

        /* Pop-up Form Styling */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .popup-form h3 {
            margin-bottom: 20px;
        }
        .popup-form label {
            display: block;
            margin-bottom: 5px;
        }
        .popup-form input, .popup-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .popup-form button {
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup-form button:hover {
            background: #34495e;
        }

        /* Success Pop-up Styling */
        .success-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1000;
        }
        .success-popup p {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .success-popup button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .success-popup button:hover {
            background: #218838;
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
        <h2>Students</h2>
        
        <!-- Search Form -->
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search students by ID, name, or course..." value="<?= htmlspecialchars($searchTerm) ?>">
                <input type="submit" value="Search">
                <a href="search_student.php" class="refresh-button">Refresh</a>
            </form>
        </div>

        <!-- Table to Display Search Results -->
        <table>
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Remaining Sessions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Loop through each row of the result set
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['idNo']}</td>
                                <td>{$row['firstName']}</td>
                                <td>{$row['lastName']}</td>
                                <td>{$row['middleName']}</td>
                                <td>{$row['course']}</td>
                                <td>{$row['year_lvl']}</td>
                                <td>{$row['remaining_sessions']}</td>
                                <td><button class='sit-in-button' onclick='openSitInForm(\"{$row['idNo']}\")'>Sit-in</button></td>
                              </tr>";
                    }
                } else {
                    // If no rows are returned, display a message
                    echo "<tr><td colspan='8' class='no-data'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pop-up Sit-in Form -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-form">
            <h3>Sit-in Form</h3>
            <form id="sitInForm" action="submit_sit_in.php" method="POST">
                <input type="hidden" name="idNo" id="studentId">
                <label for="studentName">Student Name:</label>
                <input type="text" id="studentName" readonly>
                <label for="remainingSessions">Remaining Sessions:</label>
                <input type="text" id="remainingSessions" readonly>
                <label for="purpose">Purpose:</label>
                <select name="purpose" id="purpose" required>
                    <option value="Java Programming">Java Programming</option>
                    <option value="Networking">Networking</option>
                    <option value="PHP Programming">PHP Programming</option>
                    <option value="Python Programming">Python Programming</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Others">Others</option>
                </select>
                <label for="lab">Select Lab:</label>
                <select name="lab" id="lab" required>
                    <?php
                    foreach ($labEnumValues as $lab) {
                        echo "<option value='$lab'>$lab</option>";
                    }
                    ?>
                </select>
                <button type="submit">Submit</button>
                <button type="button" onclick="closeSitInForm()">Close</button>
            </form>
        </div>
    </div>

    <!-- Success Pop-up -->
    <div class="success-popup" id="successPopup">
        <p>Sit-in recorded successfully!</p>
        <button onclick="closeSuccessPopup()">OK</button>
    </div>

    <script>
        // JavaScript to handle pop-up form
        function openSitInForm(idNo) {
            // Fetch student details using AJAX
            fetch(`fetch_student_details.php?idNo=${idNo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Populate the form fields
                        document.getElementById('studentId').value = data.idNo;
                        document.getElementById('studentName').value = `${data.firstName} ${data.lastName}`;
                        document.getElementById('remainingSessions').value = data.remaining_sessions;
                        document.getElementById('popupOverlay').style.display = 'flex'; // Show the pop-up
                    }
                })
                .catch(error => console.error('Error fetching student details:', error));
        }

        function closeSitInForm() {
            document.getElementById('popupOverlay').style.display = 'none'; // Hide the pop-up
        }

        function closeSuccessPopup() {
            document.getElementById('successPopup').style.display = 'none'; // Hide the success pop-up
        }

        // Handle form submission via AJAX
        document.getElementById('sitInForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Submit the form data using Fetch API
            fetch('submit_sit_in.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeSitInForm(); // Close the sit-in form
                    document.getElementById('successPopup').style.display = 'block'; // Show success pop-up
                } else {
                    alert(data.error || 'An error occurred. Please try again.');
                }
            })
            .catch(error => console.error('Error submitting form:', error));
        });
    </script>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>