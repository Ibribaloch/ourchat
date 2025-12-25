<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

if (!isset($_POST['id_2'])) {
    exit;
}

include '../db.conn.php';

$id_1 = $_SESSION['user_id']; // logged-in user
$id_2 = $_POST['id_2'];       // chat with user

$sql = "SELECT * FROM chats
        WHERE (from_id=? AND to_id=?)
           OR (from_id=? AND to_id=?)
        ORDER BY chat_id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_1, $id_2, $id_2, $id_1]);
if($chat['to_id'] == $_SESSION['user_id'] && $chat['status'] == 0){
    $conn->prepare("UPDATE chats SET status=1 WHERE chat_id=?")
         ->execute([$chat['chat_id']]);
}

if ($stmt->rowCount() > 0) {
    $chats = $stmt->fetchAll();

    foreach ($chats as $chat) {

        // decide side
        $isSender = ($chat['from_id'] == $id_1);
        $bubbleClass = $isSender ? 'bubble sent' : 'bubble received';
        ?>

        <div class="<?= $bubbleClass ?>" data-from="<?= $chat['from_id'] ?>">

            <?php if (!empty($chat['message'])): ?>
                <div class="text"><?= htmlspecialchars($chat['message']) ?></div>
            <?php endif; ?>

            <?php if (!empty($chat['image'])): ?>
                <img src="uploads/chat_images/<?= $chat['image'] ?>"
                     class="chat-img">
            <?php endif; ?>

            <div class="time"><?= $chat['created_at'] ?></div>
        </div>

        <?php
    }
}
