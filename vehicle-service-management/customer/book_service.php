<?php

include "../includes/config.php";
include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];

$error = "";
$success = "";

$vehicle_id = 0;
$service_type = "";
$appointment_date = "";
$appointment_time = "";
$remarks = "";


/*
|--------------------------------------------------------------------------
| GET CUSTOMER VEHICLES
|--------------------------------------------------------------------------
*/

$vehicle_query = "
    SELECT id, brand, model, year, plate_number
    FROM vehicles
    WHERE user_id = $user_id
    ORDER BY brand ASC
";

$vehicle_result = mysqli_query($conn, $vehicle_query);


/*
|--------------------------------------------------------------------------
| HANDLE FORM SUBMISSION
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $service_type = trim($_POST['service_type'] ?? '');
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $remarks = trim($_POST['remarks'] ?? '');


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $vehicle_id <= 0 ||
        empty($service_type) ||
        empty($appointment_date) ||
        empty($appointment_time)
    ) {

        $error = "Please fill in all required fields.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | VERIFY VEHICLE OWNERSHIP
        |--------------------------------------------------------------------------
        |
        | This is important.
        |
        | The customer could manually modify vehicle_id
        | in the browser.
        |
        | We therefore make sure the selected vehicle
        | actually belongs to the logged-in customer.
        |
        */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM vehicles
             WHERE id = ?
             AND user_id = ?"
        );


        mysqli_stmt_bind_param(
            $check_stmt,
            "ii",
            $vehicle_id,
            $user_id
        );


        mysqli_stmt_execute($check_stmt);


        $check_result = mysqli_stmt_get_result($check_stmt);


        $vehicle_exists = mysqli_fetch_assoc($check_result);


        mysqli_stmt_close($check_stmt);


        if (!$vehicle_exists) {

            $error = "Invalid vehicle selected.";

        } else {


            /*
            |--------------------------------------------------------------------------
            | INSERT RESERVATION
            |--------------------------------------------------------------------------
            */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO reservations
                (
                    user_id,
                    vehicle_id,
                    service_type,
                    appointment_date,
                    appointment_time,
                    remarks,
                    status,
                    mechanic_id
                )
                VALUES (?, ?, ?, ?, ?, ?, 'Pending', NULL)"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "iissss",
                $user_id,
                $vehicle_id,
                $service_type,
                $appointment_date,
                $appointment_time,
                $remarks
            );


            if (mysqli_stmt_execute($stmt)) {

                $success = "Service appointment submitted successfully.";

                /*
                 * Clear the form after successful submission.
                 */

                $vehicle_id = 0;
                $service_type = "";
                $appointment_date = "";
                $appointment_time = "";
                $remarks = "";

            } else {

                $error = "Unable to submit your service appointment. Please try again.";

            }


            mysqli_stmt_close($stmt);

        }

    }

}

?>


<div class="container-fluid">


    <!-- PAGE HEADER -->

    <div class="mb-4">

        <h2>
            Book a Service
        </h2>

        <p class="text-muted">
            Schedule a service appointment for your vehicle.
        </p>

    </div>



    <!-- SUCCESS MESSAGE -->

    <?php if (!empty($success)): ?>

        <div
            class="alert alert-success"
            role="alert"
        >

            <?= htmlspecialchars($success); ?>

        </div>

    <?php endif; ?>



    <!-- ERROR MESSAGE -->

    <?php if (!empty($error)): ?>

        <div
            class="alert alert-danger"
            role="alert"
        >

            <?= htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>



    <?php if (mysqli_num_rows($vehicle_result) > 0): ?>


        <!-- BOOKING FORM -->

        <div class="dashboard-card">


            <form
                method="POST"
                action=""
            >


                <div class="row g-4">


                    <!-- VEHICLE -->

                    <div class="col-md-12">

                        <label
                            for="vehicle_id"
                            class="form-label"
                        >

                            Select Vehicle

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            class="form-select"
                            id="vehicle_id"
                            name="vehicle_id"
                            required
                        >

                            <option value="">
                                -- Select Vehicle --
                            </option>


                            <?php while ($vehicle = mysqli_fetch_assoc($vehicle_result)): ?>

                                <option
                                    value="<?= $vehicle['id']; ?>"
                                    <?= ($vehicle_id == $vehicle['id']) ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars($vehicle['brand']); ?>

                                    <?= htmlspecialchars($vehicle['model']); ?>

                                    -
                                    <?= htmlspecialchars($vehicle['plate_number']); ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>



                    <!-- SERVICE TYPE -->

                    <div class="col-md-6">

                        <label
                            for="service_type"
                            class="form-label"
                        >

                            Service Type

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            class="form-select"
                            id="service_type"
                            name="service_type"
                            required
                        >

                            <option value="">
                                -- Select Service --
                            </option>

                            <option
                                value="General Maintenance"
                                <?= ($service_type ?? '') === 'General Maintenance' ? 'selected' : ''; ?>
                            >
                                General Maintenance
                            </option>

                            <option
                                value="Oil Change"
                                <?= ($service_type ?? '') === 'Oil Change' ? 'selected' : ''; ?>
                            >
                                Oil Change
                            </option>

                            <option
                                value="Brake Service"
                                <?= ($service_type ?? '') === 'Brake Service' ? 'selected' : ''; ?>
                            >
                                Brake Service
                            </option>

                            <option
                                value="Engine Check"
                                <?= ($service_type ?? '') === 'Engine Check' ? 'selected' : ''; ?>
                            >
                                Engine Check
                            </option>

                            <option
                                value="Battery Service"
                                <?= ($service_type ?? '') === 'Battery Service' ? 'selected' : ''; ?>
                            >
                                Battery Service
                            </option>

                            <option
                                value="Tire Service"
                                <?= ($service_type ?? '') === 'Tire Service' ? 'selected' : ''; ?>
                            >
                                Tire Service
                            </option>

                            <option
                                value="Air Conditioning Service"
                                <?= ($service_type ?? '') === 'Air Conditioning Service' ? 'selected' : ''; ?>
                            >
                                Air Conditioning Service
                            </option>

                            <option
                                value="Other"
                                <?= ($service_type ?? '') === 'Other' ? 'selected' : ''; ?>
                            >
                                Other
                            </option>

                        </select>

                    </div>



                    <!-- DATE -->

                    <div class="col-md-3">

                        <label
                            for="appointment_date"
                            class="form-label"
                        >

                            Appointment Date

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input
                            type="date"
                            class="form-control"
                            id="appointment_date"
                            name="appointment_date"
                            value="<?= htmlspecialchars($appointment_date ?? ''); ?>"
                            min="<?= date('Y-m-d'); ?>"
                            required
                        >

                    </div>



                    <!-- TIME -->

                    <div class="col-md-3">

                        <label
                            for="appointment_time"
                            class="form-label"
                        >

                            Appointment Time

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input
                            type="time"
                            class="form-control"
                            id="appointment_time"
                            name="appointment_time"
                            value="<?= htmlspecialchars($appointment_time ?? ''); ?>"
                            required
                        >

                    </div>



                    <!-- REMARKS -->

                    <div class="col-md-12">

                        <label
                            for="remarks"
                            class="form-label"
                        >

                            Remarks

                        </label>


                        <textarea
                            class="form-control"
                            id="remarks"
                            name="remarks"
                            rows="4"
                            placeholder="Describe any problem or additional information about your vehicle..."
                        ><?= htmlspecialchars($remarks ?? ''); ?></textarea>

                    </div>


                </div>



                <!-- BUTTONS -->

                <div class="d-flex gap-2 mt-4">

                    <a
                        href="dashboard.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Submit Service Request
                    </button>

                </div>


            </form>


        </div>


    <?php else: ?>


        <!-- NO VEHICLE -->

        <div class="dashboard-card text-center py-5">


            <div class="fs-1 mb-3">
                🚗
            </div>


            <h4>
                No Vehicle Registered
            </h4>


            <p class="text-muted">
                You need to register a vehicle before booking a service.
            </p>


            <a
                href="add_vehicle.php"
                class="btn btn-primary"
            >
                + Add Vehicle
            </a>


        </div>


    <?php endif; ?>


</div>


<?php

include "../includes/customer_footer.php";

?>