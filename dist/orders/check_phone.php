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

// Check if phone is provided
if (!isset($_GET['phone']) || empty(trim($_GET['phone']))) {
    echo json_encode(['exists' => false, 'message' => 'Phone number not provided']);
    exit();
}

$phone = trim($_GET['phone']);

// Validate phone format (10 digits)
if (!preg_match('/^\d{10}$/', $phone)) {
    echo json_encode(['exists' => false, 'message' => 'Invalid phone number format (must be 10 digits)']);
    exit();
}

// Prepare and execute the query to check for duplicate phone number
// Check against both 'phone' and 'phone2' columns
$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE phone = ? OR phone2 = ?");
$stmt->bind_param("ss", $phone, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Phone number exists
    echo json_encode(['exists' => true]);
} else {
    // Phone number does not exist
    echo json_encode(['exists' => false]);
}

$stmt->close();
$conn->close();
?>