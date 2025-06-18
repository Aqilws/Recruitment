<?php
// update_status.php
// header('Content-Type: application/json');

// Atur zona waktu ke WIB (Asia/Jakarta)
date_default_timezone_set("Asia/Jakarta");

// Koneksi database
require 'db.php';
// var_dump($conn);
// exit;


// Ambil data JSON dari permintaan
$data = json_decode(file_get_contents("php://input"), true);


$id = $data['id'];
$status = $data['status'];

// Validasi input
if (!$id || !$status) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

// var_dump($data);
// exit;
// Ambil waktu saat ini sebagai status_date (format MySQL DATETIME)
$status_date = date("Y-m-d H:i:s");

$status = mysqli_real_escape_string($conn, $status);
$status_date = mysqli_real_escape_string($conn, $status_date);
$id = (int)$id;

$query = "UPDATE data_reqruitment SET status = '$status', status_date = '$status_date' WHERE id = $id";

$success = mysqli_query($conn, $query);

echo json_encode(["success" => $success, "status_date" => $status_date]);
?>
