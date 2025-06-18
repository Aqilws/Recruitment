<?php
$conn = new mysqli("localhost", "root", "", "reqruitment");

$data = json_decode(file_get_contents("php://input"), true);
// Set zona waktu Indonesia (WIB)
date_default_timezone_set("Asia/Jakarta");
$tanggal = date("Y-m-d"); // otomatis tanggal hari ini

foreach ($data as $item) {
    $stmt = $conn->prepare("INSERT INTO data_reqruitment (name, age, gender, address, experience, grade, phone, email, status, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $status = "no-respon"; // default
    $stmt->bind_param("sisssissss", 
        $item['name'], $item['age'], $item['gender'], $item['address'],
        $item['experience'], $item['grade'], $item['phone'],
        $item['email'], $status, $tanggal
    );
    $stmt->execute();
}

echo json_encode(["success" => true]);
?>
