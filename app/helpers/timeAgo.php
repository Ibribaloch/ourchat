<?php

// setting up the time Zone
define('TIMEZONE', 'Asia/Karachi');
date_default_timezone_set(TIMEZONE);

function last_seen($date) {
    if (!$date) return "";

    $timestamp = strtotime($date);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return "online";
    } else {
        return date("h:i a", $timestamp);
    }
}

