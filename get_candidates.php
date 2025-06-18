<?php
require 'db.php';
$result = $conn->query("SELECT * FROM data_reqruitment");

$candidates = [];
while ($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}
echo json_encode($candidates);
?>
