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
         AND role = 'customer'
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

            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['fullname'];
            $_SESSION['customer_role'] = $user['role'];

            header("Location: customer/dashboard.php");
            exit();

        }
    }

    $error = "Invalid customer email or password.";

    mysqli_stmt_close($stmt);
}

?>

<?php include 'includes/customer_header.php'; ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Welcome Back
                        </h2>

                        <p class="text-muted">
                            Login to your Vehicle Service account
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
                                placeholder="Enter your password"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            name="login"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                    <hr class="my-4">

                    <div class="text-center">

                        <p class="mb-2 text-muted">
                            Don't have an account?
                        </p>

                        <a
                            href="register.php"
                            class="btn btn-outline-primary w-100">

                            Create an Account

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/customer_footer.php'; ?>