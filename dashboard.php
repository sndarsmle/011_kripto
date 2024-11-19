<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = strtoupper($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Sidebar styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            background-color: #2e7d32;
            color: white;
        }
        .sidebar h4 {
            font-weight: bold;
            text-transform: uppercase;
        }
        .sidebar .nav-link {
            color: white;
            transition: background-color 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: #1b5e20;
        }
        .sidebar .logout .nav-link:hover {
            background-color: #b71c1c; /* Warna merah untuk hover pada Logout */
        }
        .sidebar .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
        /* Main content styling */
        .main-content {
            margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
            padding: 20px;
        }
    </style>
</head>
<body style="background-color: #e8f5e9;">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar py-5">
                <h4 class="pl-3">JUAL BELI SAPI</h4>
                <br><br>
                <ul class="nav flex-column pl-3">
                    <li class="nav-item">
                        <a class="nav-link" href="chat_list.php">Fitur Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_file.php">Upload File</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="informasi_sapi.php">Informasi Sapi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_steganography.php">Upload Steganografi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sapi_photos.php">Foto Sapi</a>
                    </li>
                    <li class="nav-item logout">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </nav>

            <!-- Main content -->
            <main role="main" class="main-content col-md-9 ml-sm-auto col-lg-10">
                <div class="pt-3 pb-2 mb-3">
                    <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?></h2>
                    <p>Pilih menu di sebelah kiri untuk fitur yang tersedia.</p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
