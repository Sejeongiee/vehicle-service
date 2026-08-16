<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/config.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } elseif (strlen($password) < 8) {

        $error = "Password must be at least 8 characters.";

    } else {

        /* Check existing email */

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $check,
            "s",
            $email
        );

        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $error = "An account with this email already exists.";

        } else {

            /* Hash password */

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            /* Create customer account */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (fullname, email, password, role)
                VALUES (?, ?, ?, 'customer')"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $fullname,
                $email,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {

                $success =
                    "Account created successfully! You can now login.";

            } else {

                $error =
                    "Something went wrong. Please try again.";

            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}

?>

<?php include 'includes/customer_header.php'; ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Create Your Account
                        </h2>

                        <p class="text-muted">
                            Register for Vehicle Service Management
                        </p>

                    </div>

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">

                            <?= htmlspecialchars($error); ?>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($success)): ?>

                        <div class="alert alert-success">

                            <?= htmlspecialchars($success); ?>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                placeholder="Enter your full name"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
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
                                placeholder="At least 8 characters"
                                minlength="8"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                placeholder="Confirm your password"
                                minlength="8"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            name="register"
                            class="btn btn-primary w-100">

                            Create Account

                        </button>

                    </form>

                    <hr class="my-4">

                    <div class="text-center">

                        <p class="text-muted mb-2">
                            Already have an account?
                        </p>

                        <a
                            href="login.php"
                            class="btn btn-outline-secondary w-100">

                            Back to Login

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/customer_footer.php'; ?>