<?php
session_start();
require 'config.php';
require 'stegano.php';

$extractError = '';
$extractedMessage = '';
$steganoImages = [];

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil daftar gambar steganografi
$stmt = $pdo->prepare("SELECT s.steganography_image_path, u.username AS sender 
                       FROM steganography s 
                       JOIN users u ON s.user_id = u.id");
$stmt->execute();
$steganoImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['stegano_image']) || $_FILES['stegano_image']['error'] !== UPLOAD_ERR_OK) {
        $extractError = 'Gagal mengunggah gambar steganografi.';
    } elseif (!isset($_POST['key']) || strlen($_POST['key']) < 8) {
        $extractError = 'Kunci harus terdiri dari minimal 8 karakter.';
    } else {
        $steganoImage = $_FILES['stegano_image'];
        $key = $_POST['key'];

        $tempImagePath = 'uploads/temp/' . uniqid() . '_' . basename($steganoImage['name']);
        if (move_uploaded_file($steganoImage['tmp_name'], $tempImagePath)) {
            $hiddenMessage = extractMessageFromImage($tempImagePath);
            if ($hiddenMessage) {
                $extractedMessage = decryptMessage($hiddenMessage, $key);
                if (!$extractedMessage) {
                    $extractError = 'Kunci salah atau pesan tidak valid.';
                }
            } else {
                $extractError = 'Gagal mengekstrak pesan dari gambar.';
            }

            unlink($tempImagePath);
        } else {
            $extractError = 'Gagal menyimpan file sementara untuk ekstraksi.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Sapi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .custom-card {
            border: 1px solid #28a745;
            border-radius: 10px;
            padding: 20px;
        }
        .custom-btn {
            background-color: #28a745;
            color: white;
            border-radius: 10px;
        }
        .custom-btn:hover {
            background-color: #218838;
            color: white;
        }
        .table-container {
            overflow-x: auto;
        }
        .image-preview {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Foto Sapi</h2>
        <div class="row">
            <!-- KIRI: Alat Ekstrak -->
            <div class="col-md-5">
                <div class="custom-card">
                    <h4 class="text-success">Alat Ekstrak Pesan</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="stegano_image">Pilih Gambar Stegano</label>
                            <input type="file" name="stegano_image" id="stegano_image" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="key">Masukkan Kunci</label>
                            <input type="password" name="key" id="key" class="form-control" required>
                        </div>
                        <button type="submit" class="btn custom-btn btn-block">Ekstrak Pesan</button>
                    </form>
                    <?php if ($extractError): ?>
                        <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($extractError); ?></div>
                    <?php elseif ($extractedMessage): ?>
                        <div class="alert alert-info mt-3">
                            <strong>Pesan Rahasia:</strong> <?php echo htmlspecialchars($extractedMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- KANAN: Tabel Gambar -->
            <div class="col-md-7">
                <div class="custom-card">
                    <h4 class="text-success">Daftar Gambar Steganografi</h4>
                    <div class="table-container">
                        <table class="table table-bordered table-striped mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Pengirim</th>
                                    <th>Gambar</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($steganoImages as $image): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($image['sender']); ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($image['steganography_image_path']); ?>" 
                                                 alt="Gambar Stegano" 
                                                 class="image-preview">
                                        </td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($image['steganography_image_path']); ?>" 
                                               download 
                                               class="btn btn-sm btn-success">Download</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($steganoImages)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data gambar.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
