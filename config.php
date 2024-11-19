<?php
$host = 'localhost';
$dbname = 'project_akhir_kripto';
$username = 'root';
$password = '';

// Koneksi ke Database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
