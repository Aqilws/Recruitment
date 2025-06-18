<?php
require 'db.php';
$query = "SELECT * FROM data_reqruitment";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kandidat</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>Import Data Kandidat dari Excel</h2>
<?php if (isset($_GET['success'])): ?>
    <p class="success">Import berhasil!</p>
<?php endif; ?>

<form action="import.php" method="post" enctype="multipart/form-data">
    <input type="file" name="excel_file" accept=".xls,.xlsx" required>
    <button type="submit">Import</button>
</form>

<h2>Daftar Kandidat</h2>
<table>
    <thead>
        <tr>
            <th>Nama</th><th>Usia</th><th>Gender</th><th>Alamat</th><th>Pengalaman</th><th>Grade</th><th>Telepon</th><th>Email</th><th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['experience'] ?> tahun</td>
            <td><?= $row['grade'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['status'] ?? '-' ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
