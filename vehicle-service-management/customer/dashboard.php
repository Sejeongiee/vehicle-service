<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];


/* TOTAL VEHICLES */

$vehicle_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM vehicles
     WHERE user_id = $user_id"
);

$total_vehicles = mysqli_fetch_assoc(
    $vehicle_result
)['total'];


/* TOTAL RESERVATIONS */

$reservation_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE user_id = $user_id"
);

$total_reservations = mysqli_fetch_assoc(
    $reservation_result
)['total'];


/* PENDING RESERVATIONS */

$pending_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE user_id = $user_id
     AND status = 'Pending'"
);

$pending_reservations = mysqli_fetch_assoc(
    $pending_result
)['total'];

?>

<div class="container-fluid">

    <div class="mb-4">

        <h2>
            Welcome,
            <?= htmlspecialchars($_SESSION['customer_name']); ?>
        </h2>

        <p class="text-muted">
            Manage your vehicles and service appointments.
        </p>

    </div>


    <!-- STATISTICS -->

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="dashboard-card">

                <h6>
                    My Vehicles
                </h6>

                <h2>
                    <?= $total_vehicles; ?>
                </h2>

                <a
                    href="vehicles.php"
                    class="btn btn-sm btn-outline-primary mt-3"
                >
                    View Vehicles
                </a>

            </div>

        </div>


        <div class="col-md-4">

            <div class="dashboard-card">

                <h6>
                    Total Reservations
                </h6>

                <h2>
                    <?= $total_reservations; ?>
                </h2>

                <a
                    href="reservations.php"
                    class="btn btn-sm btn-outline-primary mt-3"
                >
                    View Reservations
                </a>

            </div>

        </div>


        <div class="col-md-4">

            <div class="dashboard-card">

                <h6>
                    Pending Reservations
                </h6>

                <h2>
                    <?= $pending_reservations; ?>
                </h2>

                <a
                    href="reservations.php"
                    class="btn btn-sm btn-outline-warning mt-3"
                >
                    Check Status
                </a>

            </div>

        </div>

    </div>


    <!-- QUICK ACTIONS -->

    <div class="dashboard-card">

        <h4 class="mb-4">
            Quick Actions
        </h4>

        <div class="row g-3">

            <div class="col-md-4">

                <a
                    href="add_vehicle.php"
                    class="btn btn-primary w-100 py-3"
                >
                    🚗 Add Vehicle
                </a>

            </div>

            <div class="col-md-4">

                <a
                    href="book_service.php"
                    class="btn btn-success w-100 py-3"
                >
                    🔧 Book a Service
                </a>

            </div>

            <div class="col-md-4">

                <a
                    href="service_history.php"
                    class="btn btn-secondary w-100 py-3"
                >
                    📋 Service History
                </a>

            </div>

        </div>

    </div>

</div>

<?php

include "../includes/customer_footer.php";

?>