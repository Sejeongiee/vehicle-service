<?php

include "includes/admin_header.php";


/*
|--------------------------------------------------------------------------
| GET ALL RESERVATIONS
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
        v.plate_number,

        u.fullname AS customer_name

    FROM reservations r

    INNER JOIN vehicles v
        ON r.vehicle_id = v.id

    INNER JOIN users u
        ON r.user_id = u.id

    ORDER BY
        CASE
            WHEN r.status = 'Pending' THEN 1
            WHEN r.status = 'Approved' THEN 2
            WHEN r.status = 'In Progress' THEN 3
            WHEN r.status = 'Completed' THEN 4
            WHEN r.status = 'Cancelled' THEN 5
            ELSE 6
        END,
        r.appointment_date ASC,
        r.appointment_time ASC
";


$result = mysqli_query($conn, $query);

?>

<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>
                Reservations
            </h2>

            <p class="text-muted mb-0">
                Manage customer vehicle service appointments.
            </p>

        </div>

        <div>

            <span class="badge text-bg-warning fs-6">

                <?php

                $pending_query = "
                    SELECT COUNT(*) AS total
                    FROM reservations
                    WHERE status = 'Pending'
                ";

                $pending_result =
                    mysqli_query($conn, $pending_query);

                $pending = mysqli_fetch_assoc(
                    $pending_result
                );

                ?>

                <?= $pending['total']; ?> Pending

            </span>

        </div>

    </div>


    <!-- RESERVATIONS TABLE -->

    <div class="dashboard-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Customer</th>

                        <th>Vehicle</th>

                        <th>Service</th>

                        <th>Appointment</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (
                        $result &&
                        mysqli_num_rows($result) > 0
                    ): ?>


                        <?php while (
                            $reservation =
                            mysqli_fetch_assoc($result)
                        ): ?>

                            <?php

                            $status =
                                $reservation['status'];

                            $status_class = 'secondary';

                            if (
                                $status === 'Pending'
                            ) {

                                $status_class = 'warning';

                            } elseif (
                                $status === 'Approved'
                            ) {

                                $status_class = 'primary';

                            } elseif (
                                $status === 'In Progress'
                            ) {

                                $status_class = 'info';

                            } elseif (
                                $status === 'Completed'
                            ) {

                                $status_class = 'success';

                            } elseif (
                                $status === 'Cancelled'
                            ) {

                                $status_class = 'danger';

                            }

                            ?>


                            <tr>

                                <!-- ID -->

                                <td>

                                    <strong>
                                        #<?= $reservation['id']; ?>
                                    </strong>

                                </td>


                                <!-- CUSTOMER -->

                                <td>

                                    <?= htmlspecialchars(
                                        $reservation[
                                            'customer_name'
                                        ]
                                    ); ?>

                                </td>


                                <!-- VEHICLE -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $reservation[
                                                'brand'
                                            ]
                                        ); ?>

                                        <?= htmlspecialchars(
                                            $reservation[
                                                'model'
                                            ]
                                        ); ?>

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            $reservation[
                                                'plate_number'
                                            ]
                                        ); ?>

                                    </small>

                                </td>


                                <!-- SERVICE -->

                                <td>

                                    <?= htmlspecialchars(
                                        $reservation[
                                            'service_type'
                                        ]
                                    ); ?>

                                </td>


                                <!-- APPOINTMENT -->

                                <td>

                                    <?= date(
                                        'M d, Y',
                                        strtotime(
                                            $reservation[
                                                'appointment_date'
                                            ]
                                        )
                                    ); ?>

                                    <br>

                                    <small class="text-muted">

                                        <?= date(
                                            'h:i A',
                                            strtotime(
                                                $reservation[
                                                    'appointment_time'
                                                ]
                                            )
                                        ); ?>

                                    </small>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="badge text-bg-<?= $status_class; ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $status
                                        ); ?>

                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <a
                                        href="reservation_view.php?id=<?= $reservation['id']; ?>"
                                        class="btn btn-sm btn-primary"
                                    >

                                        View

                                    </a>

                                </td>

                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="fs-1 mb-3">
                                    📅
                                </div>

                                <h5>
                                    No Reservations
                                </h5>

                                <p class="text-muted mb-0">

                                    There are currently no
                                    service reservations.

                                </p>

                            </td>

                        </tr>


                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<?php

include "includes/admin_footer.php";

?>