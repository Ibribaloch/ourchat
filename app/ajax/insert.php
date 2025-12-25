<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

if (!isset($_POST['to_id'])) {
    exit;
}

include '../db.conn.php';

$from_id = $_SESSION['user_id'];
$to_id   = $_POST['to_id'];
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

$imageName = null;

/* ================= IMAGE UPLOAD ================= */
if (!empty($_FILES['image']['name'])) {

    $uploadDir = "../../uploads/chat_images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];

    if (in_array($ext, $allowed)) {
        $imageName = uniqid("chat_", true) . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }
}

/* ================= INSERT CHAT ================= */
$sql = "INSERT INTO chats (from_id, to_id, message, image, status)
VALUES (?, ?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
$res  = $stmt->execute([$from_id, $to_id, $message, $imageName]);

if ($res) {

    date_default_timezone_set('Asia/Karachi');
    $time = date("h:i a");
    ?>

    <!-- SENDER MESSAGE (ALWAYS RIGHT) -->
    <div class="bubble sent" data-from="<?=$from_id?>">

        <?php if (!empty($message)): ?>
            <div class="text"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($imageName)): ?>
            <img src="uploads/chat_images/<?= $imageName ?>"
                 class="chat-img">
        <?php endif; ?>

        <div class="time"><?= $time ?></div>
    </div>

    <?php
}
