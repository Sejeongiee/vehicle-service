<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "includes/config.php";

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, fullname, email, password, role
         FROM users
         WHERE email = ?
         AND role = 'staff'
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['fullname'];
            $_SESSION['admin_role'] = $user['role'];

            header("Location: dashboard.php");
            exit();
        }
    }

    $error = "Invalid staff email or password.";

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Vehicle Service Management Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

</head>

<body class="admin-login-page">

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="admin-login-card">

                <div class="text-center mb-4">

                    <h2>
                        Vehicle Service
                    </h2>

                    <h4>
                        Management Admin
                    </h4>

                    <p class="text-muted">
                        Staff Portal
                    </p>

                </div>

                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Staff Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        name="login"
                        class="btn btn-primary w-100">

                        Staff Login

                    </button>

                </form>

                <div class="text-center mt-4">

                    <a
                        href="http://localhost/vehicle-service-management/">

                        ← Customer Website

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>