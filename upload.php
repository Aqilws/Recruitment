<?php
require 'autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// DB connection
$conn = new mysqli("localhost", "root", "", "reqruitment");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed']));
}

// File check
if (!isset($_FILES['excelFile'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$fileTmpPath = $_FILES['excelFile']['tmp_name'];

try {
    $spreadsheet = IOFactory::load($fileTmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    // Assuming first row is header
    for ($i = 1; $i < count($data); $i++) {
        $row = $data[$i];
        
        // Sesuaikan urutan kolom Excel: name, age, gender, address, experience, grade, phone, email
        $name = $conn->real_escape_string($row[0]);
        $age = (int)$row[1];
        $gender = $conn->real_escape_string($row[2]);
        $address = $conn->real_escape_string($row[3]);
        $experience = (int)$row[4];
        $grade = $conn->real_escape_string($row[5]);
        $phone = $conn->real_escape_string($row[6]);
        $email = $conn->real_escape_string($row[7]);
        $status = 'no-respon'; // default status

        $sql = "INSERT INTO data_reqruitment (name, age, gender, address, experience, grade, phone, email, status)
                VALUES ('$name', $age, '$gender', '$address', $experience, '$grade', '$phone', '$email', '$status')";

        $conn->query($sql);
    }

    echo json_encode(['success' => true, 'message' => 'Data successfully imported into database']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
