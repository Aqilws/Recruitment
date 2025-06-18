<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Jakarta");

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$comment = $data['comment'];

if (!$id || $comment === null) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$stmt = $conn->prepare("UPDATE data_reqruitment SET comment = ? WHERE id = ?");
$stmt->bind_param("si", $comment, $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(["success" => $success]);
?>
