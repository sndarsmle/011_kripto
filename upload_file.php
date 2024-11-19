<?php
session_start();
require 'config.php';
require 'file_encryption.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['key'];
    $file = $_FILES['file'];

    // Daftar tipe file yang diizinkan
    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];

    if (!empty($file['name']) && !empty($key)) {
        if (in_array($file['type'], $allowedTypes)) {
            $originalPath = 'uploads/' . basename($file['name']);
            $encryptedPath = 'encrypted_files/' . 'encrypted_' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $originalPath);

            // Enkripsi file
            $encryptedContent = aesEncryptFile($originalPath, $key);
            file_put_contents($encryptedPath, $encryptedContent);

            // Simpan ke database
            $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, encrypted_file_path) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $file['name'], $encryptedPath]);

            $message = "<div class='alert alert-success'>File berhasil dienkripsi dan diunggah.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Tipe file tidak didukung! Hanya file Word, PDF, dan TXT yang diizinkan.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Pastikan file dan kunci diisi!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload dan Enkripsi File</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-success, .btn-secondary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h4 class="text-center mb-4" style="color: #28a745;">Upload dan Enkripsi File</h4>

        <!-- Tampilkan pesan jika ada -->
        <?php if (!empty($message)) echo $message; ?>

        <form action="upload_file.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Pilih File:</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.txt" required>
            </div>
            <div class="form-group">
                <label for="key">Masukkan Kunci:</label>
                <input type="text" name="key" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mb-2">Unggah dan Enkripsi</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </form>
    </div>
</body>
</html>
