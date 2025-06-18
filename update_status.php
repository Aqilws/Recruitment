<?php
// update_status.php
header('Content-Type: application/json');

// Atur zona waktu ke WIB (Asia/Jakarta)
date_default_timezone_set("Asia/Jakarta");

// Koneksi database
$conn = new mysqli("localhost", "root", "", "reqruitment");

// Ambil data JSON dari permintaan
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$status = $data['status'];

// Validasi input
if (!$id || !$status) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

// Ambil waktu saat ini sebagai status_date (format MySQL DATETIME)
$status_date = date("Y-m-d H:i:s");

// Update status dan status_date ke database
$stmt = $conn->prepare("UPDATE data_reqruitment SET status = ?, status_date = ? WHERE id = ?");
$stmt->bind_param("ssi", $status, $status_date, $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(["success" => $success, "status_date" => $status_date]);
?>
