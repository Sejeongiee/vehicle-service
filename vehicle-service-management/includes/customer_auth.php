<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['customer_id'])) {

    header("Location: /vehicle-service-management/login.php");
    exit();

}

if (
    !isset($_SESSION['customer_role']) ||
    $_SESSION['customer_role'] !== 'customer'
) {

    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_role']);

    header("Location: /vehicle-service-management/login.php");
    exit();

}