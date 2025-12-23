<?php
session_start();

if (isset($_SESSION['username'])) {

    if (isset($_POST['id_2'])) {

        include '../db.conn.php';

        $id_1 = $_SESSION['user_id'];
        $id_2 = $_POST['id_2'];

        $sql = "SELECT * FROM chats
                WHERE (from_id=? AND to_id=?) OR (from_id=? AND to_id=?)
                ORDER BY chat_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_1, $id_2, $id_2, $id_1]);

        if ($stmt->rowCount() > 0) {
            $chats = $stmt->fetchAll();

            foreach ($chats as $chat) {
                $class = ($chat['from_id'] == $id_1) ? 'rtext align-self-end' : 'ltext';
?>
<p class="<?=$class?>" data-chat-id="<?=$chat['chat_id']?>" data-from="<?=$chat['from_id']?>">

    <?php if ($chat['message']): ?>
        <?= htmlspecialchars($chat['message']) ?><br>
    <?php endif; ?>
    <?php if (!empty($chat['image'])): ?>
        <img src="uploads/chat_images/<?= $chat['image'] ?>" class="img-fluid rounded mt-1" style="max-width:200px;">
    <?php endif; ?>
    <small class="d-block"><?= $chat['created_at'] ?></small>
</p>
<?php


            }
        }
    }

} else {
    header("Location: ../../index.php");
    exit;
}
