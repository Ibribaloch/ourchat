<?php
function opened($chat_with, $conn, $chats){
    if (empty($chats)) return;

    foreach ($chats as $chat) {
        if ($chat['to_id'] == $_SESSION['user_id'] && $chat['status'] < 2) {
            $sql = "UPDATE chats SET status = 2 WHERE chat_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$chat['chat_id']]);
        }
    }
}
