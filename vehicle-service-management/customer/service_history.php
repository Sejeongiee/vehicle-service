<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];


/*
|--------------------------------------------------------------------------
| GET COMPLETED SERVICES
|--------------------------------------------------------------------------
|
| Service history should only show completed services.
|
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
        v.year,
        v.plate_number,
        v.color,

        m.fullname AS mechanic_name,
        m.specialization AS mechanic_specialization

    FROM reservations r

    INNER JOIN vehicles v
        ON r.vehicle_id = v.id

    LEFT JOIN mechanics m
        ON r.mechanic_id = m.id

    WHERE r.user_id = ?
      AND r.status = 'Completed'

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
            Service History
        </h2>

        <p class="text-muted">
            View the completed services for your vehicles.
        </p>

    </div>



    <!-- SERVICE HISTORY -->

    <div class="dashboard-card">


        <?php if (mysqli_num_rows($result) > 0): ?>


            <div class="table-responsive">

                <table class="table table-hover align-middle">


                    <thead>

                        <tr>

                            <th>Vehicle</th>

                            <th>Service</th>

                            <th>Date</th>

                            <th>Mechanic</th>

                            <th>Remarks</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php while ($service = mysqli_fetch_assoc($result)): ?>


                            <tr>


                                <!-- VEHICLE -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $service['brand']
                                        ); ?>

                                        <?= htmlspecialchars(
                                            $service['model']
                                        ); ?>

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            $service['plate_number']
                                        ); ?>

                                    </small>

                                </td>



                                <!-- SERVICE -->

                                <td>

                                    <?= htmlspecialchars(
                                        $service['service_type']
                                    ); ?>

                                </td>



                                <!-- DATE -->

                                <td>

                                    <?= date(
                                        'M d, Y',
                                        strtotime(
                                            $service['appointment_date']
                                        )
                                    ); ?>

                                    <br>

                                    <small class="text-muted">

                                        <?= date(
                                            'h:i A',
                                            strtotime(
                                                $service['appointment_time']
                                            )
                                        ); ?>

                                    </small>

                                </td>



                                <!-- MECHANIC -->

                                <td>

                                    <?php if (
                                        !empty($service['mechanic_name'])
                                    ): ?>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $service['mechanic_name']
                                            ); ?>

                                        </strong>

                                        <?php if (
                                            !empty(
                                                $service[
                                                    'mechanic_specialization'
                                                ]
                                            )
                                        ): ?>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars(
                                                    $service[
                                                        'mechanic_specialization'
                                                    ]
                                                ); ?>

                                            </small>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Not assigned
                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- REMARKS -->

                                <td>

                                    <?php if (
                                        !empty($service['remarks'])
                                    ): ?>

                                        <?= htmlspecialchars(
                                            $service['remarks']
                                        ); ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <span class="badge text-bg-success">

                                        <?= htmlspecialchars(
                                            $service['status']
                                        ); ?>

                                    </span>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    </tbody>


                </table>

            </div>


        <?php else: ?>


            <!-- NO HISTORY -->

            <div class="text-center py-5">


                <div class="fs-1 mb-3">
                    📋
                </div>


                <h4>
                    No Service History Yet
                </h4>


                <p class="text-muted">

                    You don't have any completed vehicle
                    services yet.

                </p>


                <a
                    href="book_service.php"
                    class="btn btn-primary"
                >

                    Book a Service

                </a>


            </div>


        <?php endif; ?>


    </div>


</div>



<?php

mysqli_stmt_close($stmt);

include "../includes/customer_footer.php";

?>