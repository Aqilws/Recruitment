<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jumlah'])) {
    $jumlah = (int) $_POST['jumlah'];

    // Set zona waktu Indonesia (WIB)
date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // Ubah status dan set status_date
    $sql = "UPDATE data_reqruitment 
            SET status = 'on-progress', status_date = ? 
            WHERE id IN (
                SELECT id FROM (
                    SELECT id FROM data_reqruitment 
                    WHERE status = 'Baru' 
                    ORDER BY id ASC 
                    LIMIT ?
                ) AS temp
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $now, $jumlah);
    $stmt->execute();

    header("Location: index.php?updated=success");
    exit;
}
?>
