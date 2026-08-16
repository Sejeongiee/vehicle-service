<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";

$user_id = $_SESSION['customer_id'];

$success = "";
$error = "";


/* HANDLE FORM SUBMISSION */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $plate_number = trim($_POST['plate_number'] ?? '');
    $color = trim($_POST['color'] ?? '');


    /* VALIDATION */

    if (
        empty($brand) ||
        empty($model) ||
        empty($year) ||
        empty($plate_number)
    ) {

        $error = "Please fill in all required fields.";

    } elseif (!is_numeric($year) || strlen($year) != 4) {

        $error = "Please enter a valid 4-digit year.";

    } else {

        /* INSERT VEHICLE */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO vehicles
            (user_id, brand, model, year, plate_number, color)
            VALUES (?, ?, ?, ?, ?, ?)"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "ississ",
            $user_id,
            $brand,
            $model,
            $year,
            $plate_number,
            $color
        );


        if (mysqli_stmt_execute($stmt)) {

            $success = "Vehicle added successfully.";

            /*
             * Clear form values after successful insertion
             */

            $brand = "";
            $model = "";
            $year = "";
            $plate_number = "";
            $color = "";

        } else {

            $error = "Unable to add vehicle. Please try again.";

        }


        mysqli_stmt_close($stmt);

    }

}

?>


<div class="container-fluid">

    <!-- PAGE HEADER -->

    <div class="mb-4">

        <h2>
            Add Vehicle
        </h2>

        <p class="text-muted">
            Register a vehicle to your account.
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


    <!-- FORM CARD -->

    <div class="dashboard-card">

        <form
            method="POST"
            action=""
        >

            <div class="row g-4">


                <!-- BRAND -->

                <div class="col-md-6">

                    <label
                        for="brand"
                        class="form-label"
                    >
                        Vehicle Brand
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="brand"
                        name="brand"
                        value="<?= htmlspecialchars($brand ?? ''); ?>"
                        placeholder="e.g. Toyota"
                        required
                    >

                </div>


                <!-- MODEL -->

                <div class="col-md-6">

                    <label
                        for="model"
                        class="form-label"
                    >
                        Vehicle Model
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="model"
                        name="model"
                        value="<?= htmlspecialchars($model ?? ''); ?>"
                        placeholder="e.g. Vios"
                        required
                    >

                </div>


                <!-- YEAR -->

                <div class="col-md-6">

                    <label
                        for="year"
                        class="form-label"
                    >
                        Year
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="year"
                        name="year"
                        value="<?= htmlspecialchars($year ?? ''); ?>"
                        placeholder="e.g. 2022"
                        min="1900"
                        max="2100"
                        required
                    >

                </div>


                <!-- PLATE NUMBER -->

                <div class="col-md-6">

                    <label
                        for="plate_number"
                        class="form-label"
                    >
                        Plate Number
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="plate_number"
                        name="plate_number"
                        value="<?= htmlspecialchars($plate_number ?? ''); ?>"
                        placeholder="e.g. ABC-1234"
                        required
                    >

                </div>


                <!-- COLOR -->

                <div class="col-md-6">

                    <label
                        for="color"
                        class="form-label"
                    >
                        Color
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="color"
                        name="color"
                        value="<?= htmlspecialchars($color ?? ''); ?>"
                        placeholder="e.g. White"
                    >

                </div>


            </div>


            <!-- BUTTONS -->

            <div class="d-flex gap-2 mt-4">

                <a
                    href="vehicles.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Add Vehicle
                </button>

            </div>

        </form>

    </div>

</div>


<?php

include "../includes/customer_footer.php";

?>