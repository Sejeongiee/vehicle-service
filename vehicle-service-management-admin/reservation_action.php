<?php

include "includes/admin_auth.php";
include "includes/config.php";


/*
|--------------------------------------------------------------------------
| GET REQUEST VALUES
|--------------------------------------------------------------------------
*/

$reservation_id = intval(
    $_POST['reservation_id'] ?? 0
);

$action = $_POST['action'] ?? '';


/*
|--------------------------------------------------------------------------
| VALIDATE REQUEST
|--------------------------------------------------------------------------
*/

if ($reservation_id <= 0 || empty($action)) {

    header("Location: reservations.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| GET CURRENT RESERVATION
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, status
     FROM reservations
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $reservation_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$reservation = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$reservation) {

    header("Location: reservations.php");
    exit;

}


$current_status = $reservation['status'];


/*
|--------------------------------------------------------------------------
| APPROVE RESERVATION
|--------------------------------------------------------------------------
*/

if ($action === 'approve') {

    /*
     * Only Pending reservations can be approved.
     */

    if ($current_status === 'Pending') {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservations
             SET status = 'Approved'
             WHERE id = ?
             AND status = 'Pending'"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $reservation_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }


    header(
        "Location: reservation_view.php?id="
        . $reservation_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| REJECT / CANCEL RESERVATION
|--------------------------------------------------------------------------
*/

if ($action === 'cancel') {

    /*
     * Only Pending or Approved reservations
     * can be cancelled by admin.
     */

    if (
        $current_status === 'Pending' ||
        $current_status === 'Approved'
    ) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservations
             SET status = 'Cancelled'
             WHERE id = ?
             AND status IN ('Pending', 'Approved')"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $reservation_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }


    header(
        "Location: reservation_view.php?id="
        . $reservation_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| START SERVICE
|--------------------------------------------------------------------------
*/

if ($action === 'start') {

    /*
     * Only Approved reservations can
     * be moved to In Progress.
     */

    if ($current_status === 'Approved') {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservations
             SET status = 'In Progress'
             WHERE id = ?
             AND status = 'Approved'"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $reservation_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }


    header(
        "Location: reservation_view.php?id="
        . $reservation_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| COMPLETE SERVICE
|--------------------------------------------------------------------------
*/

if ($action === 'complete') {

    /*
     * Only In Progress reservations can
     * be marked Completed.
     */

    if ($current_status === 'In Progress') {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservations
             SET status = 'Completed'
             WHERE id = ?
             AND status = 'In Progress'"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $reservation_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }


    header(
        "Location: reservation_view.php?id="
        . $reservation_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| UNKNOWN ACTION
|--------------------------------------------------------------------------
*/

header(
    "Location: reservation_view.php?id="
    . $reservation_id
);

exit;