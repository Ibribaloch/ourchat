<?php
session_start();

if (isset($_SESSION['user_id'])) {

    include '../db.conn.php';

    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET last_seen = NOW() WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
}
