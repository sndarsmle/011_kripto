<?php
session_start();
include 'config.php';
include 'encryption.php'; // Menggunakan algoritma Caesar + AES

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

if (!$message || !$receiver_id) {
    die("Data tidak valid.");
}

// Enkripsi pesan menggunakan Caesar + AES
$encrypted_message = encryptMessage($message);

// Simpan pesan terenkripsi ke database
$stmt = $pdo->prepare("
    INSERT INTO messages (sender_id, receiver_id, message)
    VALUES (:sender_id, :receiver_id, :message)
");
$stmt->execute([
    'sender_id' => $current_user_id,
    'receiver_id' => $receiver_id,
    'message' => $encrypted_message
]);

// Kembali ke ruang obrolan
header("Location: chat_room.php?receiver_id=$receiver_id");
exit;
?>
