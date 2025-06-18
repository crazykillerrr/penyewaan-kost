<?php
session_start();

// Redirect based on user type
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit();
} elseif (isset($_SESSION['user_id'])) {
    header('Location: user/dashboard.php');
    exit();
} else {
    header('Location: login_user.php');
    exit();
}
?>
