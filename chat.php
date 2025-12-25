<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

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
<title>Chat</title>

<link rel="stylesheet" href="css/chat-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head><script>
    setTimeout(function(){
        location.reload();
    }, 30000); // 30 seconds
</script>
<body>

<div class="wa-container">

    <!-- HEADER -->
    <div class="wa-header">
        <a href="home.php" class="back">←</a>
        <img src="uploads/<?=$chatWith['p_p']?>" class="avatar">
        <div class="info">
            <div class="name"><?=$chatWith['name']?></div>
            <div class="status">
                <?= last_seen($chatWith['last_seen']) == "Active" ? "online" : last_seen($chatWith['last_seen']) ?>
            </div>
        </div>
    </div>

    <!-- CHAT BOX -->
    <div class="wa-chat" id="chatBox">
        <?php foreach ($chats as $chat): ?>
            <div class="bubble <?=($chat['from_id']==$_SESSION['user_id'])?'sent':'received'?>" 
                 data-from="<?=$chat['from_id']?>">

                <?php if(!empty($chat['message'])): ?>
                    <div class="text"><?=htmlspecialchars($chat['message'])?></div>
                <?php endif; ?>

                <?php if(!empty($chat['image'])): ?>
                    <img src="uploads/chat_images/<?=$chat['image']?>" class="chat-img">
                <?php endif; ?>

                <div class="time"><div class="time">
    <?=$chat['created_at']?>

    <?php if($chat['from_id'] == $_SESSION['user_id']): ?>
        <span class="ticks 
        <?=$chat['status']==2?'seen':($chat['status']==1?'delivered':'sent')?>">✓✓</span>
    <?php endif; ?>
</div>
</div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- INPUT -->
    <div class="wa-input">
        <input type="file" id="imageInput" hidden>
        <button id="attachBtn"><i class="fa fa-paperclip"></i></button>
        <input type="text" id="message" placeholder="Type a message">
        <button id="sendBtn"><i class="fa fa-paper-plane"></i></button>
    </div>

</div>
<!-- IMAGE PREVIEW MODAL -->
<div id="imgPreviewModal" class="img-modal">
    <span class="close-preview">&times;</span>
    <img id="previewImg">
    <button id="sendImageBtn">Send</button>
</div>


<!-- SOUND -->
<audio id="notificationSound" src="notification.mp3" preload="auto"></audio>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
let lastCount = $("#chatBox .bubble").length;

function scrollDown(){
    let box = document.getElementById("chatBox");
    box.scrollTop = box.scrollHeight;
}
scrollDown();

/* attach image */
$("#attachBtn").click(function(){
    $("#imageInput").click();
});

let selectedImage = null;

/* image select */
$("#imageInput").on("change", function(){
    selectedImage = this.files[0];
    if(!selectedImage) return;

    let reader = new FileReader();
    reader.onload = function(e){
        $("#previewImg").attr("src", e.target.result);
        $("#imgPreviewModal").fadeIn();
    }
    reader.readAsDataURL(selectedImage);
});

/* close preview */
$(".close-preview").click(function(){
    $("#imgPreviewModal").fadeOut();
    $("#imageInput").val("");
    selectedImage = null;
});

/* send image from preview */
$("#sendImageBtn").click(function(){
    sendMessage();
    $("#imgPreviewModal").fadeOut();
});

/* send button text / text only */
$("#sendBtn").click(function(){
    sendMessage();
});

/* SEND FUNCTION */
function sendMessage(){
    let msg = $("#message").val();
    if(msg=="" && !selectedImage) return;

    let fd = new FormData();
    fd.append("message", msg);
    fd.append("to_id", <?=$chatWith['user_id']?>);
    if(selectedImage) fd.append("image", selectedImage);

    $.ajax({
        url:"app/ajax/insert.php",
        type:"POST",
        data:fd,
        contentType:false,
        processData:false,
        success:function(data){
            $("#chatBox").append(data);
            $("#message").val("");
            $("#imageInput").val("");
            selectedImage = null;
            scrollDown();
        }
    });
}

/* IMAGE CLICK → FULL SCREEN VIEW */
$(document).on("click",".chat-img",function(){
    $("#previewImg").attr("src",$(this).attr("src"));
    $("#sendImageBtn").hide();
    $("#imgPreviewModal").fadeIn();
});

$(".close-preview").click(function(){
    $("#sendImageBtn").show();
});

/* fetch messages */
function fetchData(){
    $.post("app/ajax/getMessage.php",{id_2: <?=$chatWith['user_id']?>},function(data){
        let bubbles = $(data).filter(".bubble");
        let count = bubbles.length;

        if(count > lastCount){
            let last = bubbles.last();
            if(last.data("from") != <?=$_SESSION['user_id']?>){
                document.getElementById("notificationSound").play().catch(()=>{});
            }
            $("#chatBox").html(data);
            scrollDown();
        }
        lastCount = count;
    });
}

setInterval(fetchData, 1000);
</script>

</body>
</html>

