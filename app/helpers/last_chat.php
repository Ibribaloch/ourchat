<?php
/**
 * last_chat.php
 * 
 * Helper function to get the last message between logged-in user and another user
 */

function lastChat($user_id, $chatWith_id, $conn){
    // Fetch the most recent message between the two users
    $sql = "SELECT message, image 
            FROM chats 
            WHERE (from_id=? AND to_id=?) OR (from_id=? AND to_id=?) 
            ORDER BY chat_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $chatWith_id, $chatWith_id, $user_id]);
    
    if($stmt->rowCount() > 0){
        $chat = $stmt->fetch();

        // Return message + image indicator
        if(!empty($chat['image']) && !empty($chat['message'])){
            return "📷 " . substr($chat['message'], 0, 30); // image + text preview
        } elseif(!empty($chat['image'])){
            return "📷 Image"; // only image
        } else {
            return substr($chat['message'], 0, 30); // only text
        }
    }

    return ""; // no message yet
}

/**
 * Check if last message from chatWith_id to user_id is opened
 * Returns true if opened, false if unread
 */
function lastChatOpened($user_id, $chatWith_id, $conn){
    $sql = "SELECT opened 
            FROM chats 
            WHERE from_id=? AND to_id=? 
            ORDER BY chat_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$chatWith_id, $user_id]);

    if($stmt->rowCount() > 0){
        $chat = $stmt->fetch();
        return $chat['opened'] == 1; // true if opened, false if unread
    }

    return true; // No unread messages
}
?>
