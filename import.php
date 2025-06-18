<?php
require 'db.php';
require 'vendor/autoload.php'; // composer autoload

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['excel_file']['tmp_name'])) {
    $filePath = $_FILES['excel_file']['tmp_name'];
    $fileType = $_FILES['excel_file']['type'];

    $allowedTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ];

    if (!in_array($fileType, $allowedTypes)) {
        die("Format file tidak valid.");
    }

    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $inserted = 0;
        $skipped = 0;
        $tanggal = date("Y-m-d"); // ambil tanggal hari ini

        // Mulai dari baris ke-2 (lewati header)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $name = $row[0] ?? '';
            $age = $row[1] ?? 0;
            $gender = $row[2] ?? '';
            $address = $row[3] ?? '';
            $experience = $row[4] ?? '';
            $grade = $row[5] ?? '';
            $phone = $row[6] ?? '';
            $email = $row[7] ?? '';
            $status = 'Baru'; // default status awal

            // Cek apakah nama sudah ada di database
            $stmt_check = $conn->prepare("SELECT id FROM data_reqruitment WHERE name = ?");
            $stmt_check->bind_param("s", $name);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows == 0 && $name != '') {
                // Insert hanya jika belum ada
                $stmt_insert = $conn->prepare("INSERT INTO data_reqruitment (name, age, gender, address, experience, grade, phone, email, status, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("sissssssss", $name, $age, $gender, $address, $experience, $grade, $phone, $email, $status, $tanggal);
                $stmt_insert->execute();
                $inserted++;
            } else {
                $skipped++;
            }
        }

        // Redirect atau tampilkan informasi sukses
        header("Location: index.php?imported=$inserted&skipped=$skipped");
        exit;
    } catch (Exception $e) {
        die("Gagal memproses file: " . $e->getMessage());
    }
}
?>
