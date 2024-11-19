<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$username = strtoupper($_SESSION['username']);

// Ambil daftar pengguna lain
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE id != :current_user_id");
$stmt->execute(['current_user_id' => $current_user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #E8F5E9; /* Hijau lembut */
            font-family: Arial, sans-serif;
        }
        .chat-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .chat-header {
            background-color: #4CAF50; /* Hijau utama */
            color: #ffffff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header a {
            color: #ffffff;
            text-decoration: none;
            font-size: 1.1rem;
        }
        .chat-header a:hover {
            text-decoration: underline;
        }
        .chat-header .title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .chat-list {
            padding: 15px;
        }
        .chat-list .list-group-item {
            border: none;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-size: 1.1rem;
            color: #333;
        }
        .chat-list .list-group-item:last-child {
            border-bottom: none;
        }
        .chat-list .list-group-item:hover {
            background-color: #E8F5E9; /* Hijau lembut */
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <a href="dashboard.php">&larr; Dashboard</a>
        <div class="title">Chat List</div>
    </div>
    <div class="chat-list">
        <div class="list-group">
            <?php foreach ($users as $user): ?>
                <a href="chat_room.php?receiver_id=<?php echo $user['id']; ?>" class="list-group-item">
                    <?php echo htmlspecialchars($user['username']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
