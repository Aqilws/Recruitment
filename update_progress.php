<?php
header('Content-Type: application/json');
require 'db.php';

// Set zona waktu Indonesia (WIB)
date_default_timezone_set("Asia/Jakarta");

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$progress = $data['progress'];
$status = $data['status'];
$status_date = date("Y-m-d H:i:s"); // tanggal dan waktu WIB

if (!$id || !is_numeric($progress)) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$stmt = $conn->prepare("UPDATE data_reqruitment SET progress = ?, status = ?, status_date = ? WHERE id = ?");
$stmt->bind_param("issi", $progress, $status, $status_date, $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode([
    "success" => $success,
    "status_date" => $status_date
]);
?>
