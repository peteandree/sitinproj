<?php
include "admin_db_connection.php"; // Database connection

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="sit_in_data.xls"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Subject', 'Number of Sit-ins']); // Headers

$sql = "SELECT purpose, COUNT(*) AS sitin_count FROM sit_in_records GROUP BY purpose";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['purpose'], $row['sitin_count']], "\t"); // Use tab delimiter for Excel
    }
}

fclose($output);
exit;
?>
