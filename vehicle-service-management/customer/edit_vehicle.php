<?php

include "../includes/customer_auth.php";
include "../includes/customer_header.php";


$user_id = $_SESSION['customer_id'];

$error = "";
$success = "";


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
| GET VEHICLE
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We check BOTH:
|
| vehicle id
| AND
| logged-in customer's user_id
|
| This prevents customers from editing another customer's vehicle.
|
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM vehicles
     WHERE id = ?
     AND user_id = ?"
);


mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $vehicle_id,
    $user_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$vehicle = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| VEHICLE NOT FOUND
|--------------------------------------------------------------------------
*/

if (!$vehicle) {

    header("Location: vehicles.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| HANDLE UPDATE
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $plate_number = trim($_POST['plate_number'] ?? '');
    $color = trim($_POST['color'] ?? '');


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

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


        /*
        |--------------------------------------------------------------------------
        | UPDATE VEHICLE
        |--------------------------------------------------------------------------
        */

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE vehicles
             SET brand = ?,
                 model = ?,
                 year = ?,
                 plate_number = ?,
                 color = ?
             WHERE id = ?
             AND user_id = ?"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "ssissii",
            $brand,
            $model,
            $year,
            $plate_number,
            $color,
            $vehicle_id,
            $user_id
        );


        if (mysqli_stmt_execute($stmt)) {

            $success = "Vehicle updated successfully.";


            /*
            |--------------------------------------------------------------------------
            | UPDATE DISPLAYED DATA
            |--------------------------------------------------------------------------
            */

            $vehicle['brand'] = $brand;
            $vehicle['model'] = $model;
            $vehicle['year'] = $year;
            $vehicle['plate_number'] = $plate_number;
            $vehicle['color'] = $color;

        } else {

            $error = "Unable to update vehicle. Please try again.";

        }


        mysqli_stmt_close($stmt);

    }

}

?>


<div class="container-fluid">


    <!-- PAGE HEADER -->

    <div class="mb-4">

        <h2>
            Edit Vehicle
        </h2>

        <p class="text-muted">
            Update your vehicle information.
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



    <!-- FORM -->

    <div class="dashboard-card">


        <form
            method="POST"
            action="edit_vehicle.php?id=<?= $vehicle_id; ?>"
        >


            <div class="row g-4">


                <!-- BRAND -->

                <div class="col-md-6">

                    <label
                        for="brand"
                        class="form-label"
                    >

                        Vehicle Brand

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="brand"
                        name="brand"
                        value="<?= htmlspecialchars($vehicle['brand']); ?>"
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

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="model"
                        name="model"
                        value="<?= htmlspecialchars($vehicle['model']); ?>"
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

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="number"
                        class="form-control"
                        id="year"
                        name="year"
                        value="<?= htmlspecialchars($vehicle['year']); ?>"
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

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="plate_number"
                        name="plate_number"
                        value="<?= htmlspecialchars($vehicle['plate_number']); ?>"
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
                        value="<?= htmlspecialchars($vehicle['color'] ?? ''); ?>"
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
                    Save Changes
                </button>


            </div>


        </form>


    </div>


</div>


<?php

include "../includes/customer_footer.php";

?>