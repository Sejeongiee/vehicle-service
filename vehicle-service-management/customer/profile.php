<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];

$error = "";
$success = "";


/*
|--------------------------------------------------------------------------
| UPDATE PROFILE
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_profile'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);


    if (empty($fullname) || empty($email)) {

        $error = "Full name and email are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | CHECK IF EMAIL IS ALREADY USED
        |--------------------------------------------------------------------------
        */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM users
             WHERE email = ?
             AND id != ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "si",
            $email,
            $user_id
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);


        if (mysqli_num_rows($check_result) > 0) {

            $error = "That email address is already being used.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER INFORMATION
            |--------------------------------------------------------------------------
            */

            $update_stmt = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET fullname = ?, email = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $update_stmt,
                "ssi",
                $fullname,
                $email,
                $user_id
            );


            if (mysqli_stmt_execute($update_stmt)) {

                /*
                |--------------------------------------------------------------------------
                | UPDATE SESSION NAME
                |--------------------------------------------------------------------------
                */

                $_SESSION['customer_name'] = $fullname;

                $success = "Profile updated successfully.";

            } else {

                $error = "Unable to update your profile.";

            }


            mysqli_stmt_close($update_stmt);
        }


        mysqli_stmt_close($check_stmt);
    }
}


/*
|--------------------------------------------------------------------------
| CHANGE PASSWORD
|--------------------------------------------------------------------------
*/

if (isset($_POST['change_password'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT PASSWORD
    |--------------------------------------------------------------------------
    */

    $password_stmt = mysqli_prepare(
        $conn,
        "SELECT password
         FROM users
         WHERE id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $password_stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($password_stmt);

    $password_result =
        mysqli_stmt_get_result($password_stmt);

    $user = mysqli_fetch_assoc($password_result);


    /*
    |--------------------------------------------------------------------------
    | VERIFY CURRENT PASSWORD
    |--------------------------------------------------------------------------
    */

    if (
        !$user ||
        !password_verify(
            $current_password,
            $user['password']
        )
    ) {

        $error = "Current password is incorrect.";

    } elseif (strlen($new_password) < 8) {

        $error = "New password must be at least 8 characters.";

    } elseif ($new_password !== $confirm_password) {

        $error = "New passwords do not match.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | HASH NEW PASSWORD
        |--------------------------------------------------------------------------
        */

        $hashed_password =
            password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        */

        $update_password_stmt = mysqli_prepare(
            $conn,
            "UPDATE users
             SET password = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update_password_stmt,
            "si",
            $hashed_password,
            $user_id
        );


        if (
            mysqli_stmt_execute(
                $update_password_stmt
            )
        ) {

            $success =
                "Password changed successfully.";

        } else {

            $error =
                "Unable to change your password.";

        }


        mysqli_stmt_close(
            $update_password_stmt
        );
    }


    mysqli_stmt_close($password_stmt);
}


/*
|--------------------------------------------------------------------------
| GET CURRENT CUSTOMER INFORMATION
|--------------------------------------------------------------------------
*/

$user_stmt = mysqli_prepare(
    $conn,
    "SELECT id, fullname, email, role, created_at
     FROM users
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($user_stmt);

$user_result =
    mysqli_stmt_get_result($user_stmt);

$customer = mysqli_fetch_assoc($user_result);

mysqli_stmt_close($user_stmt);

?>


<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="mb-4">

        <h2>
            My Profile
        </h2>

        <p class="text-muted">
            Manage your account information and password.
        </p>

    </div>


    <!-- ALERTS -->

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


    <div class="row g-4">


        <!-- PROFILE INFORMATION -->

        <div class="col-lg-6">

            <div class="dashboard-card">

                <h4 class="mb-4">
                    Account Information
                </h4>


                <form method="POST">


                    <!-- FULL NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="fullname"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $customer['fullname']
                            ); ?>"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $customer['email']
                            ); ?>"
                            required
                        >

                    </div>


                    <!-- ACCOUNT ROLE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Account Type
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Customer"
                            readonly
                        >

                    </div>


                    <!-- ACCOUNT CREATED -->

                    <div class="mb-4">

                        <label class="form-label">
                            Member Since
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= date(
                                'M d, Y',
                                strtotime(
                                    $customer['created_at']
                                )
                            ); ?>"
                            readonly
                        >

                    </div>


                    <button
                        type="submit"
                        name="update_profile"
                        class="btn btn-primary">

                        Save Changes

                    </button>


                </form>

            </div>

        </div>



        <!-- CHANGE PASSWORD -->

        <div class="col-lg-6">

            <div class="dashboard-card">

                <h4 class="mb-4">
                    Change Password
                </h4>


                <form method="POST">


                    <!-- CURRENT PASSWORD -->

                    <div class="mb-3">

                        <label class="form-label">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- NEW PASSWORD -->

                    <div class="mb-3">

                        <label class="form-label">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="new_password"
                            class="form-control"
                            minlength="8"
                            required
                        >

                        <small class="text-muted">
                            Password must be at least 8 characters.
                        </small>

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            minlength="8"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        name="change_password"
                        class="btn btn-warning">

                        Change Password

                    </button>


                </form>

            </div>

        </div>


    </div>


</div>


<?php

include "../includes/customer_footer.php";

?>