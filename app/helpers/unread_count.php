<?php

function hasUnread($from_id, $to_id, $conn) {
    $sql = "SELECT chat_id FROM chats 
            WHERE from_id = ? 
            AND to_id = ? 
            AND opened = 0
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$from_id, $to_id]);

    return $stmt->rowCount() > 0;
}
