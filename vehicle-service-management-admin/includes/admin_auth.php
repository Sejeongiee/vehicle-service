<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (
    !isset($_SESSION['admin_role']) ||
    $_SESSION['admin_role'] !== 'staff'
) {
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}