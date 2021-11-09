<?php

// COMMENTS FOR JOHN
// Question 12
//
// This sessions.php file contains all functions relating to sessions.
// These have been placed into their own file for seperation of concerns.

session_start();

// COMMENTS FOR JOHN
// Question 13
//
// Rate limiter delares the time of last use, the current time and then calculates the difference.
// If the difference is less than a second, the rate limiter activates.
function rateLimit_PerSecond() {
    if (isset($_SESSION['LAST_CALL'])) {
        $last = strtotime($_SESSION['LAST_CALL']);
        $curr = strtotime(date("Y-m-d h:i:s"));
        $sec_diff =  abs($last - $curr);
        if ($sec_diff < 1) {
           return true;
        }
    }
    $_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");
}

// COMMENTS FOR JOHN
// Question 13
//
// Rate limiter delares 24hours prior and checks the user_logs in database.
// If more than 1000 rows exist for a user, the rate limiter activates.
function rateLimit_PerDay() {
    $dbconn = db_connect();
    $dayago = strtotime('-1 day');
    $curruser =  $_SESSION['user'];
    $sql = "SELECT user FROM user_logs WHERE timestamp < $dayago AND user = $curruser";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() < 1000) {
        return true;
    }
    return false;
}

// Domain lock, only localhost is an accepted origin
function domainLock() {
    $origin = "http://localhost:3000/";
    $authorised = ["http://localhost:8888",
    "http://localhost:3000/"];

    if (in_array($origin, $authorised)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        return true;
    }
    return false;
}