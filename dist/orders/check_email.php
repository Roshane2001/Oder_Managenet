<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['exists' => false, 'message' => 'Unauthorized']);
    exit();
}

// Include database connection
include($_SERVER['DOCUMENT_ROOT'] . '/order_management/dist/connection/db_connection.php');

// Check if email is provided
if (!isset($_GET['email']) || empty(trim($_GET['email']))) {
    echo json_encode(['exists' => false, 'message' => 'Email not provided']);
    exit();
}

$email = trim($_GET['email']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['exists' => false, 'message' => 'Invalid email format']);
    exit();
}

// Prepare and execute the query to check for duplicate email
$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists
    echo json_encode(['exists' => true]);
} else {
    // Email does not exist
    echo json_encode(['exists' => false]);
}

$stmt->close();
$conn->close();
?>