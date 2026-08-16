<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];

$deleted = $_GET['deleted'] ?? '';


/* GET CUSTOMER VEHICLES */

$query = "
    SELECT *
    FROM vehicles
    WHERE user_id = $user_id
    ORDER BY id DESC
";

$result = mysqli_query($conn, $query);

?>


<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <?php if ($deleted === '1'): ?>

            <div class="alert alert-success mt-3">
                Vehicle deleted successfully.
            </div>

        <?php elseif ($deleted === '0'): ?>

            <div class="alert alert-danger mt-3">
                Unable to delete vehicle. Please try again.
            </div>

        <?php endif; ?>

        <div>

            <h2>
                My Vehicles
            </h2>

            <p class="text-muted mb-0">
                Manage the vehicles registered to your account.
            </p>

        </div>


        <a
            href="add_vehicle.php"
            class="btn btn-primary"
        >
            + Add Vehicle
        </a>

    </div>



    <!-- VEHICLES -->

    <div class="row g-4">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <?php while ($vehicle = mysqli_fetch_assoc($result)): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="dashboard-card h-100">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <h5 class="mb-1">

                                    <?= htmlspecialchars(
                                        $vehicle['brand']
                                    ); ?>

                                </h5>


                                <p class="text-muted mb-3">

                                    <?= htmlspecialchars(
                                        $vehicle['model']
                                    ); ?>

                                </p>

                            </div>


                            <span class="fs-3">
                                🚗
                            </span>

                        </div>


                        <hr>


                        <div class="vehicle-details">

                            <p>

                                <strong>
                                    Plate Number:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['plate_number']
                                ); ?>

                            </p>


                            <p>

                                <strong>
                                    Year:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['year']
                                ); ?>

                            </p>


                            <p>

                                <strong>
                                    Color:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['color'] ?? 'N/A'
                                ); ?>

                            </p>

                        </div>


                        <div class="d-flex gap-2 mt-3">

                            <a
                                href="edit_vehicle.php?id=<?= $vehicle['id']; ?>"
                                class="btn btn-outline-primary btn-sm flex-fill"
                            >
                                Edit
                            </a>


                            <a
                                href="delete_vehicle.php?id=<?= $vehicle['id']; ?>"
                                class="btn btn-outline-danger btn-sm flex-fill"
                                onclick="return confirm('Are you sure you want to delete this vehicle?');"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>


        <?php else: ?>


            <!-- NO VEHICLES -->

            <div class="col-12">

                <div class="dashboard-card text-center py-5">

                    <div class="fs-1 mb-3">
                        🚗
                    </div>


                    <h4>
                        No Vehicles Registered
                    </h4>


                    <p class="text-muted">

                        You haven't added a vehicle
                        to your account yet.

                    </p>


                    <a
                        href="add_vehicle.php"
                        class="btn btn-primary"
                    >
                        + Add Your First Vehicle
                    </a>

                </div>

            </div>


        <?php endif; ?>

    </div>

</div>


<?php

include "../includes/customer_footer.php";

?><?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];


/* GET CUSTOMER VEHICLES */

$query = "
    SELECT *
    FROM vehicles
    WHERE user_id = $user_id
    ORDER BY id DESC
";

$result = mysqli_query($conn, $query);

?>


<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>
                My Vehicles
            </h2>

            <p class="text-muted mb-0">
                Manage the vehicles registered to your account.
            </p>

        </div>


        <a
            href="add_vehicle.php"
            class="btn btn-primary"
        >
            + Add Vehicle
        </a>

    </div>



    <!-- VEHICLES -->

    <div class="row g-4">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <?php while ($vehicle = mysqli_fetch_assoc($result)): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="dashboard-card h-100">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <h5 class="mb-1">

                                    <?= htmlspecialchars(
                                        $vehicle['brand']
                                    ); ?>

                                </h5>


                                <p class="text-muted mb-3">

                                    <?= htmlspecialchars(
                                        $vehicle['model']
                                    ); ?>

                                </p>

                            </div>


                            <span class="fs-3">
                                🚗
                            </span>

                        </div>


                        <hr>


                        <div class="vehicle-details">

                            <p>

                                <strong>
                                    Plate Number:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['plate_number']
                                ); ?>

                            </p>


                            <p>

                                <strong>
                                    Year:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['year']
                                ); ?>

                            </p>


                            <p>

                                <strong>
                                    Color:
                                </strong>

                                <br>

                                <?= htmlspecialchars(
                                    $vehicle['color'] ?? 'N/A'
                                ); ?>

                            </p>

                        </div>


                        <div class="d-flex gap-2 mt-3">

                            <a
                                href="edit_vehicle.php?id=<?= $vehicle['id']; ?>"
                                class="btn btn-outline-primary btn-sm flex-fill"
                            >
                                Edit
                            </a>


                            <a
                                href="delete_vehicle.php?id=<?= $vehicle['id']; ?>"
                                class="btn btn-outline-danger btn-sm flex-fill"
                                onclick="return confirm('Are you sure you want to delete this vehicle?');"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>


        <?php else: ?>


            <!-- NO VEHICLES -->

            <div class="col-12">

                <div class="dashboard-card text-center py-5">

                    <div class="fs-1 mb-3">
                        🚗
                    </div>


                    <h4>
                        No Vehicles Registered
                    </h4>


                    <p class="text-muted">

                        You haven't added a vehicle
                        to your account yet.

                    </p>


                    <a
                        href="add_vehicle.php"
                        class="btn btn-primary"
                    >
                        + Add Your First Vehicle
                    </a>

                </div>

            </div>


        <?php endif; ?>

    </div>

</div>


<?php

include "../includes/customer_footer.php";

?>