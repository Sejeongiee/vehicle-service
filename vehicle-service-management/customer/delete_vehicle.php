<?php

include "../includes/customer_auth.php";
include "../includes/config.php";

$user_id = $_SESSION['customer_id'];


/*
|--------------------------------------------------------------------------
| GET VEHICLE ID
|--------------------------------------------------------------------------
*/

$vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($vehicle_id <= 0) {

    header("Location: vehicles.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| DELETE VEHICLE
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We check BOTH:
|
| vehicle ID
| AND
| logged-in customer's user_id
|
| This prevents a customer from deleting another customer's vehicle.
|
*/

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM vehicles
     WHERE id = ?
     AND user_id = ?"
);


mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $vehicle_id,
    $user_id
);


if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: vehicles.php?deleted=1");
    exit;

}


mysqli_stmt_close($stmt);

header("Location: vehicles.php?deleted=0");
exit;

?>