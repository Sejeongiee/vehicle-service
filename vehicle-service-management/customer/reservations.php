<?php

include "../includes/config.php";
include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];


/*
|--------------------------------------------------------------------------
| GET CUSTOMER RESERVATIONS
|--------------------------------------------------------------------------
*/

$query = "
    SELECT
        r.id,
        r.service_type,
        r.appointment_date,
        r.appointment_time,
        r.remarks,
        r.status,
        r.created_at,

        v.brand,
        v.model,
        v.plate_number

    FROM reservations r

    INNER JOIN vehicles v
        ON r.vehicle_id = v.id

    WHERE r.user_id = ?

    ORDER BY
        r.appointment_date DESC,
        r.appointment_time DESC
";


$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="mb-4">

        <h2>
            My Reservations
        </h2>

        <p class="text-muted">
            View and track your vehicle service appointments.
        </p>

    </div>


    <!-- BOOK SERVICE BUTTON -->

    <div class="mb-4">

        <a
            href="book_service.php"
            class="btn btn-primary"
        >
            + Book a Service
        </a>

    </div>


    <!-- RESERVATIONS -->

    <div class="dashboard-card">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Vehicle</th>

                            <th>Service</th>

                            <th>Date</th>

                            <th>Time</th>

                            <th>Mechanic</th>

                            <th>Status</th>

                            <th>Remarks</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($reservation = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <!-- VEHICLE -->

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($reservation['brand']); ?>
                                        <?= htmlspecialchars($reservation['model']); ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <?= htmlspecialchars($reservation['plate_number']); ?>

                                    </small>

                                </td>


                                <!-- SERVICE -->

                                <td>

                                    <?= htmlspecialchars($reservation['service_type']); ?>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <?= date(
                                        'M d, Y',
                                        strtotime($reservation['appointment_date'])
                                    ); ?>

                                </td>


                                <!-- TIME -->

                                <td>

                                    <?= date(
                                        'h:i A',
                                        strtotime($reservation['appointment_time'])
                                    ); ?>

                                </td>

                                <!-- MECHANIC -->

                                 <td>

                                    <span class="text-muted">
                                        Not assigned
                                    </span>

                                </td>

                                <!-- STATUS -->

                                <td>

                                    <?php

                                    $status = $reservation['status'];

                                    $status_class = 'secondary';

                                    if ($status === 'Pending') {
                                        $status_class = 'warning';
                                    } elseif ($status === 'Approved') {
                                        $status_class = 'primary';
                                    } elseif ($status === 'In Progress') {
                                        $status_class = 'info';
                                    } elseif ($status === 'Completed') {
                                        $status_class = 'success';
                                    } elseif ($status === 'Cancelled') {
                                        $status_class = 'danger';
                                    }

                                    ?>

                                    <span
                                        class="badge text-bg-<?= $status_class; ?>"
                                    >

                                        <?= htmlspecialchars($status); ?>

                                    </span>

                                </td>


                                <!-- REMARKS -->

                                <td>

                                    <?php if (!empty($reservation['remarks'])): ?>

                                        <?= htmlspecialchars($reservation['remarks']); ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <!-- NO RESERVATIONS -->

            <div class="text-center py-5">

                <div class="fs-1 mb-3">
                    📅
                </div>

                <h4>
                    No Reservations Yet
                </h4>

                <p class="text-muted">
                    You have not booked a vehicle service yet.
                </p>

                <a
                    href="book_service.php"
                    class="btn btn-primary"
                >
                    Book Your First Service
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php

mysqli_stmt_close($stmt);

include "../includes/customer_footer.php";

?>