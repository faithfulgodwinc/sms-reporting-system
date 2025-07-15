<?php
header('Content-Type: application/json');

// DB Config
$conn = new mysqli("localhost", "root", "", "sms_reporting");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// Get filters from query string
$month = $_GET['month'] ?? '';
$telco = $_GET['telco'] ?? '';

$query = "SELECT * FROM messages WHERE 1=1";

if (!empty($month)) {
    $query .= " AND DATE_FORMAT(sent_at, '%Y-%m') = '$month'";
}

if (!empty($telco)) {
    $query .= " AND telco = '$telco'";
}

$query .= " ORDER BY sent_at DESC";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
