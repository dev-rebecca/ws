<?php

// Password regex
function password_regexCheck ($password) {
    if (preg_match('/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,}$/', $password)) { // Min 8 chars, min 1 capital, min 1 special char, min 1 number
        return true;
    } else {
        return false;
    }
}

// Email regex
function email_regexCheck ($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Email format only
       return true;
    } else {
        return false;
    }
}

// Letters only regex
function letter_regexCheck ($text) {
    if (preg_match('/^[a-zA-Z !#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]{2,30}$/', $text)) { // Letters only, min 2, max 30
        return true;
    } else {
        return false;
    }
}

// Long text, letters only regex
function letterLong_regexCheck ($text) {
    if (preg_match('/^[a-zA-Z !#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]{2,250}$/', $text)) { // Letters only, min 2, max 30
        return true;
    } else {
        return false;
    }
}

// Numbers only regex
function number_regexCheck ($number) {
    if (ctype_digit($number)) { // Letters only, min 2, max 30
        return true;
    } else {
        return false;
    }
}

// Login check
function isUserLoggedIn() {
    if ($_SESSION['is-logged-in'] == true) {
        return true;
    } else {
        return false;
    }
}

// Logout
function doLogout() {
    $_SESSION['is-logged-in'] = false;
    session_unset();
    session_destroy();
    return;
}