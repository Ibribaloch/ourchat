<?php
session_start();

if (isset($_SESSION['username'])) {

    if (isset($_POST['to_id'])) {

        include '../db.conn.php';

        $message = isset($_POST['message']) ? trim($_POST['message']) : null;
        $to_id   = $_POST['to_id'];
        $from_id = $_SESSION['user_id'];

        $imageName = null;

        // IMAGE UPLOAD
        if (!empty($_FILES['image']['name'])) {

            $uploadDir = "../../uploads/chat_images/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid("chat_", true) . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }

        // INSERT CHAT
        $sql = "INSERT INTO chats (from_id, to_id, message, image)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $res  = $stmt->execute([$from_id, $to_id, $message, $imageName]);

        if ($res) {

            date_default_timezone_set('Asia/Karachi');
            $time = date("h:i a");
            ?>

            <p class="rtext align-self-end border rounded p-2 mb-1">
                <?php if ($message): ?>
                    <?= htmlspecialchars($message) ?><br>
                <?php endif; ?>

                <?php if ($imageName): ?>
                    <img src="uploads/chat_images/<?= $imageName ?>"
                         class="img-fluid rounded mt-1"
                         style="max-width:200px;">
                <?php endif; ?>

                <small class="d-block"><?= $time ?></small>
            </p>

            <?php
        }
    }
} else {
    header("Location: ../../index.php");
    exit;
}
