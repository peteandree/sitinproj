<?php
// Database connection (using PDO)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sit_in_monitoring', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["message" => "Database connection failed: " . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request (assume it's JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the data
    if (empty($data['idNo']) || empty($data['userName']) || empty($data['password']) || empty($data['email']) || empty($data['yearLevel']) || empty($data['address'])) {
        echo json_encode(["message" => "All fields are required."]);
        exit;
    }

    // Check if ID No. and Year Level are integers
    if (!is_numeric($data['idNo']) || !is_numeric($data['yearLevel'])) {
        echo json_encode(["message" => "ID No. and Year Level must be valid integers."]);
        exit;
    }

    // Insert user data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (idNo, firstName, lastName, middleName, course, yearLevel, email, address, userName, password) 
                               VALUES (:idNo, :firstName, :lastName, :middleName, :course, :yearLevel, :email, :address, :userName, :password)");
        $stmt->execute([
            ':idNo' => $data['idNo'],
            ':firstname' => $data['firstName'],
            ':lastname' => $data['lastName'],
            ':middlename' => $data['middleName'],
            ':course' => $data['course'],
            ':yearLevel' => $data['yearLevel'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':username' => $data['userName'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT) // Hash password
        ]);
        
        echo json_encode(["message" => "Registration successful."]);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
}
?>