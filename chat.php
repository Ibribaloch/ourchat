<?php 
session_start();

if (isset($_SESSION['username'])) {
    include 'app/db.conn.php';
    include 'app/helpers/user.php';
    include 'app/helpers/chat.php';
    include 'app/helpers/opened.php';
    include 'app/helpers/timeAgo.php';

    if (!isset($_GET['user'])) {
        header("Location: home.php");
        exit;
    }

    $chatWith = getUser($_GET['user'], $conn);

    if (empty($chatWith)) {
        header("Location: home.php");
        exit;
    }

    $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);
    opened($chatWith['user_id'], $conn, $chats);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 shadow p-4 rounded">
        <a href="home.php" class="fs-4 link-dark">&#8592;</a>
        <div class="d-flex align-items-center mb-2">
            <img src="uploads/<?=$chatWith['p_p']?>" class="w-15 rounded-circle">
            <h3 class="display-4 fs-sm m-2">
                <?=$chatWith['name']?><br>
                <div class="d-flex align-items-center" title="online">
                    <?php if (last_seen($chatWith['last_seen']) == "Active") { ?>
                        <div class="online"></div>
                        <small class="d-block p-1">Online</small>
                    <?php } else { ?>
                        <small class="d-block p-1">Last seen: <?=last_seen($chatWith['last_seen'])?></small>
                    <?php } ?>
                </div>
            </h3>
        </div>

        <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
            <?php 
            if (!empty($chats)) {
                foreach($chats as $chat) {
                    $class = ($chat['from_id'] == $_SESSION['user_id']) ? 'rtext align-self-end' : 'ltext'; ?>
                    <p class="<?=$class?> border rounded p-2 mb-1" data-from="<?=$chat['from_id']?>">
                        <?php if ($chat['message']): ?>
                            <?= htmlspecialchars($chat['message']) ?><br>
                        <?php endif; ?>
                        <?php if (!empty($chat['image'])): ?>
                            <img src="uploads/chat_images/<?= $chat['image'] ?>" class="img-fluid rounded mt-1" style="max-width:200px;">
                        <?php endif; ?>
                        <small class="d-block"><?= $chat['created_at'] ?></small>
                    </p>
                <?php }
            } else { ?>
                <div class="alert alert-info text-center">
                    <i class="fa fa-comments d-block fs-big"></i>
                    No messages yet, Start the conversation
                </div>
            <?php } ?>
        </div>

        <!-- Chat input -->
        <div class="input-group mb-3">
            <textarea cols="3" id="message" class="form-control" placeholder="Type a message"></textarea>
            <input type="file" id="imageInput" accept="image/*" class="form-control">
            <button class="btn btn-primary" id="sendBtn">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
        <div id="imagePreview" class="mb-2"></div>

    </div>

    <!-- Notification sound -->
    <audio id="notificationSound" src="notification.mp3" preload="auto"></audio>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    let lastMessageCount = $("#chatBox p").length;

    function scrollDown() {
        let chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    scrollDown();

    // Request notification permission on first click
    $(document).one("click", function() {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    });

    function showBrowserNotification(message) {
        if(Notification.permission === "granted") {
            new Notification("New message", {
                body: message,
                icon: "uploads/<?=$chatWith['p_p']?>"
            });
        }
    }

    // Image preview
    $("#imageInput").on('change', function(){
        const preview = $("#imagePreview");
        preview.html('');
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                preview.append('<img src="'+e.target.result+'" class="img-fluid rounded" style="max-width:150px;">');
            }
            reader.readAsDataURL(file);
        }
    });

    // Send message + image
    $("#sendBtn").on('click', function(e){
        e.preventDefault();
        const message = $("#message").val();
        const file = $("#imageInput")[0].files[0];
        if(!message && !file) return;

        const formData = new FormData();
        formData.append('message', message);
        formData.append('to_id', <?=$chatWith['user_id']?>);
        if(file) formData.append('image', file);

        $.ajax({
            url: 'app/ajax/insert.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                $("#message").val('');
                $("#imageInput").val('');
                $("#imagePreview").html('');
                $("#chatBox").append(data);
                scrollDown();
            }
        });
    });

    // Auto-update last seen
    function updateLastSeen() {
        $.get("app/ajax/update_last_seen.php");
    }
    updateLastSeen();
    setInterval(updateLastSeen, 10000);

    // Auto-refresh chat
   function fetchData() {
    $.post("app/ajax/getMessage.php", { id_2: <?=$chatWith['user_id']?> }, function(data){
        const chatBox = $("#chatBox");
        const newMessages = $(data).filter("p");
        const newCount = newMessages.length;

        // Append new messages
        chatBox.html(data);
        scrollDown();

        // Play sound & show notification only if last message is from other user
        if(newCount > lastMessageCount){
            const lastMsg = newMessages.last();
            const fromId = lastMsg.data("from");
            if(fromId != <?=$user_id = $_SESSION['user_id']?>){
                document.getElementById("notificationSound").play().catch(e=>console.log(e));
                showBrowserNotification(lastMsg.text());
            }
        }

        lastMessageCount = newCount;
		setInterval(fetchData, 1000);
    });
}
</script>

</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>
