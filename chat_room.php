<?php
session_start();
include 'config.php';
include 'encryption.php'; // Menggunakan algoritma Caesar + AES

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;

if (!$receiver_id) {
    die("Pengguna tidak ditemukan.");
}

// Ambil nama penerima
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = :receiver_id");
$stmt->execute(['receiver_id' => $receiver_id]);
$receiver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receiver) {
    die("Penerima tidak valid.");
}

// Ambil pesan antara dua pengguna
$stmt = $pdo->prepare("
    SELECT * FROM messages
    WHERE (sender_id = :current_user_id AND receiver_id = :receiver_id)
       OR (sender_id = :receiver_id AND receiver_id = :current_user_id)
    ORDER BY timestamp ASC
");
$stmt->execute([
    'current_user_id' => $current_user_id,
    'receiver_id' => $receiver_id
]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f1f1f1;
        }

        .chat-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 80vh; /* Membuat tinggi fleksibel */
        }

        .chat-header {
            background-color: #28a745;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .chat-box {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .chat-message {
            display: inline-block;
            max-width: 70%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            position: relative;
            word-wrap: break-word;
            clear: both;
        }

        .chat-message.sent {
            background-color: #dcf8c6;
            float: right;
            text-align: right;
        }

        .chat-message.received {
            background-color: #ffffff;
            border: 1px solid #ddd;
            float: left;
        }

        .chat-message small {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #888;
        }

        .chat-footer {
            border-top: 1px solid #ddd;
            padding: 15px;
            display: flex;
            gap: 10px;
            background-color: white;
        }

        .chat-footer textarea {
            flex: 1;
            resize: none;
        }

        .chat-footer button {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .chat-container {
                height: 90vh;
            }

            .chat-footer textarea {
                font-size: 14px;
            }

            .chat-footer button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header d-flex align-items-center">
        <a href="chat_list.php" class="btn btn-light btn-sm mr-2" title="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
            </svg>
        </a>
        <span class="text-uppercase">
            <?php echo htmlspecialchars($receiver['username']); ?>
        </span>
    </div>
    <div class="chat-box">
        <?php foreach ($messages as $message): ?>
            <div class="chat-message <?php echo $message['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                <p>
                    <?php
                    // Dekripsi pesan dengan Caesar + AES
                    $decrypted_message = decryptMessage($message['message']);
                    echo htmlspecialchars($decrypted_message);
                    ?>
                </p>
                <small><?php echo $message['timestamp']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <form action="send_message.php" method="POST" class="chat-footer">
        <textarea name="message" class="form-control" placeholder="Ketik pesan..." required></textarea>
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <button type="submit" class="btn btn-success">Kirim</button>
    </form>
</div>
</body>
</html>
