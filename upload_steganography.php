<?php
session_start();
require 'config.php';
require 'stegano.php';

$error = '';
$success = '';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedFormats = ['image/png', 'image/jpeg', 'image/jpg'];

    if (!isset($_FILES['cover_image']) || !in_array($_FILES['cover_image']['type'], $allowedFormats)) {
        $error = 'Format gambar tidak didukung. Hanya PNG, JPG, dan JPEG yang diperbolehkan.';
    } elseif ($_FILES['cover_image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Terjadi kesalahan saat mengunggah gambar.';
    } else {
        $coverImage = $_FILES['cover_image'];
        $secretMessage = $_POST['secret_message'];
        $key = $_POST['key'];

        if (strlen($key) < 8) {
            $error = 'Kunci harus terdiri dari minimal 8 karakter.';
        } elseif (empty($secretMessage)) {
            $error = 'Pesan rahasia tidak boleh kosong.';
        } else {
            $coverImagePath = 'uploads/cover_images/' . uniqid() . '_' . basename($coverImage['name']);
            if (move_uploaded_file($coverImage['tmp_name'], $coverImagePath)) {
                $encryptedMessage = encryptMessage($secretMessage, $key);
                $steganoImagePath = 'uploads/stegano_images/' . uniqid() . '_stegano.png';

                if (hideMessageInImage($coverImagePath, $encryptedMessage, $steganoImagePath)) {
                    $stmt = $pdo->prepare("INSERT INTO steganography (user_id, cover_image_path, secret_message, steganography_image_path) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_SESSION['user_id'], $coverImagePath, $encryptedMessage, $steganoImagePath]);

                    $success = 'Pesan berhasil disisipkan ke dalam gambar!';
                } else {
                    $error = 'Gagal menyisipkan pesan ke dalam gambar.';
                }
            } else {
                $error = 'Gagal menyimpan gambar cover.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Steganografi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        h2 {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Upload Steganografi</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cover_image">Pilih Gambar Cover</label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="secret_message">Pesan Rahasia</label>
                    <textarea name="secret_message" id="secret_message" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="key">Kunci</label>
                    <input type="password" name="key" id="key" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Sisipkan Pesan</button>
                <a href="dashboard.php" class="btn btn-secondary btn-block">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
</body>
</html>

