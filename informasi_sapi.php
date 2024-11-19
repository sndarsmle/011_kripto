<?php
session_start();
require 'config.php';
require 'file_encryption.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT users.username, files.filename, files.encrypted_file_path FROM files JOIN users ON files.user_id = users.id");
$stmt->execute();
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Sapi - File Terenkripsi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #28a745;
        }
        h2, h3 {
            color: #28a745;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #28a745;
            border-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
            color: #28a745;
        }
        .btn {
            border-radius: 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
        }
        .btn:hover {
            background-color: #218838;
        }
        .alert {
            border-left: 5px solid #28a745;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .custom-file-input {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .custom-file-input:hover {
            background-color: #218838;
        }
        .left-panel {
            max-height: 100vh;
            overflow: hidden;
        }
        .right-panel {
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        
        
        <div class="row">
            <!-- Panel Kiri: Alat Dekripsi File -->
            <div class="col-md-4 left-panel">
                <h3>Alat Dekripsi File</h3>
                <form action="informasi_sapi.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="encrypted_file">Pilih File Terenkripsi:</label>
                        <input type="file" name="encrypted_file" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="key">Masukkan Kunci:</label>
                        <input type="text" name="key" class="form-control" required>
                    </div>
                    <button type="submit" name="decrypt" class="btn btn-block">Dekripsi dan Download</button>
                    <a href="dashboard.php" class="btn btn-secondary btn-block">Kembali ke Dashboard</a>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decrypt'])) {
                    $key = $_POST['key'];
                    $encryptedFile = $_FILES['encrypted_file']['tmp_name'];

                    if (empty($key)) {
                        echo "<div class='alert alert-danger mt-3'>⚠️ Kunci tidak boleh kosong. Silakan coba lagi.</div>";
                    } elseif (!is_uploaded_file($encryptedFile)) {
                        echo "<div class='alert alert-danger mt-3'>⚠️ File tidak valid. Harap pilih file terenkripsi.</div>";
                    } else {
                        $decryptedContent = aesDecryptFile($encryptedFile, $key);

                        if ($decryptedContent !== false) {
                            $decryptedPath = 'decrypted_files/' . 'decrypted_' . $_FILES['encrypted_file']['name'];
                            file_put_contents($decryptedPath, $decryptedContent);
                            echo "<div class='alert alert-success mt-3'>✔️ Dekripsi berhasil! <a href='$decryptedPath' class='btn btn-sm btn-success'>Download File Hasil Dekripsi</a></div>";
                        } else {
                            echo "<div class='alert alert-danger mt-3'>❌ Dekripsi gagal. Pastikan kunci benar atau file terenkripsi valid.</div>";
                        }
                    }
                }
                ?>
            </div>
            
            <!-- Panel Kanan: Tabel Informasi Sapi -->
            <div class="col-md-8 right-panel">
                <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr style="background-color: #28a745; color: white;">
                                <th>Nama Pengirim</th>
                                <th>Nama File</th>
                                <th>Download File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($file['username']); ?></td>
                                    <td><?php echo htmlspecialchars($file['filename']); ?></td>
                                    <td><a href="<?php echo $file['encrypted_file_path']; ?>" class="btn btn-sm" download>Download</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
