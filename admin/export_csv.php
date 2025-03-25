<?php
include "admin_db_connection.php"; // Include your database connection file

// Fetch sit-ins by subject
$sql = "SELECT purpose, COUNT(*) AS sitin_count FROM sit_in_records GROUP BY purpose";
$result = $conn->query($sql);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sit_in_data.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Write CSV headers
fputcsv($output, ['Subject', 'Number of Sit-ins']);

// Write data to CSV
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['purpose'], $row['sitin_count']]);
    }
}

fclose($output);
exit;
?>