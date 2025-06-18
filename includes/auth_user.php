<?php
session_start();

function checkUserAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login_user.php');
        exit();
    }
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>
