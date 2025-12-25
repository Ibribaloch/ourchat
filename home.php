<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'app/db.conn.php';
include 'app/helpers/user.php';
include 'app/helpers/conversations.php';
include 'app/helpers/timeAgo.php';
include 'app/helpers/last_chat.php';
include 'app/helpers/unread_count.php';

$user = getUser($_SESSION['username'], $conn);
$conversations = getConversation($user['user_id'], $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WhatsApp | Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<div class="wa-container">

    <!-- HEADER -->
    <div class="wa-header">
        <div class="user-info">
            <img src="uploads/<?= $user['p_p'] ?>" class="avatar">
            <span><?= $user['name'] ?></span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- SEARCH -->
    <div class="wa-search">
        <i class="fa fa-search"></i>
        <input type="text" id="searchText" placeholder="Search or start new chat">
    </div>

    <!-- CHAT LIST -->
    <div id="chatList" class="wa-chat-list">

        <?php if (!empty($conversations)) { ?>
            <?php foreach ($conversations as $conversation) { ?>

                <a href="chat.php?user=<?= $conversation['username'] ?>" class="wa-chat-item">

                    <div class="avatar-box">
                        <img src="uploads/<?= $conversation['p_p'] ?>" class="avatar">

                        <?php if (last_seen($conversation['last_seen']) == "Active") { ?>
                            <span class="online-dot"></span>
                        <?php } ?>
                    </div>

                    <div class="chat-info">
                        <div class="chat-top">
                            <span class="chat-name"><?= $conversation['name'] ?></span>

                            <?php if (hasUnread($conversation['user_id'], $user['user_id'], $conn)) { ?>
                                <span class="unread-dot"></span>
                            <?php } ?>
                        </div>

                        <div class="chat-last">
                            <?= lastChat($user['user_id'], $conversation['user_id'], $conn) ?>
                        </div>
                    </div>

                </a>

            <?php } ?>
        <?php } else { ?>
            <div class="no-chat">
                No chats yet
            </div>
        <?php } ?>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$("#searchText").on("input", function(){
    let val = $(this).val();
    if(val === "") return;

    $.post("app/ajax/search.php", { key: val }, function(data){
        $("#chatList").html(data);
    });
});
</script>

</body>
</html>
