<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Vehicle Service Management
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Customer CSS -->
    <link
        rel="stylesheet"
        href="/vehicle-service-management/css/customer.css"
    >

</head>

<body>

<?php if (isset($_SESSION['customer_id'])): ?>

<nav class="customer-navbar">

    <div class="navbar-container">

        <a
            href="/vehicle-service-management/customer/dashboard.php"
            class="navbar-brand"
        >
            🚗 VSMS
        </a>

        <ul class="navbar-menu">

            <li>
                <a href="/vehicle-service-management/customer/dashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/customer/vehicles.php">
                    My Vehicles
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/customer/book_service.php">
                    Book Service
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/customer/reservations.php">
                    Reservations
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/customer/service_history.php">
                    Service History
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/customer/profile.php">
                    Profile
                </a>
            </li>

        </ul>

        <div class="navbar-user">

            <span>
                <?= htmlspecialchars($_SESSION['customer_name']); ?>
            </span>

            <a
                href="/vehicle-service-management/customer/logout.php"
                class="btn btn-outline-danger btn-sm"
            >
                Logout
            </a>

        </div>

    </div>

</nav>

<?php else: ?>

<nav class="customer-navbar">

    <div class="navbar-container">

        <a
            href="/vehicle-service-management/"
            class="navbar-brand"
        >
            🚗 VSMS
        </a>

        <ul class="navbar-menu">

            <li>
                <a href="/vehicle-service-management/">
                    Home
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/services.php">
                    Services
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/about.php">
                    About
                </a>
            </li>

            <li>
                <a href="/vehicle-service-management/contact.php">
                    Contact
                </a>
            </li>

        </ul>

        <div class="navbar-user">

            <a
                href="/vehicle-service-management/login.php"
                class="btn btn-outline-primary btn-sm"
            >
                Login
            </a>

            <a
                href="/vehicle-service-management/register.php"
                class="btn btn-primary btn-sm"
            >
                Register
            </a>

        </div>

    </div>

</nav>

<?php endif; ?>


<main class="customer-main">