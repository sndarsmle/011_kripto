<?php
if (isset($_GET['path'])) {
    $filePath = $_GET['path'];

    // Pastikan file ada
    if (file_exists($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($filePath);
        exit;
    } else {
        echo "File tidak ditemukan!";
    }
}
?>
