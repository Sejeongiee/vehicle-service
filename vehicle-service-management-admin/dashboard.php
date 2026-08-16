<?php

include "includes/admin_header.php";

$total = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM reservations"
    )
)['total'];

$pending = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM reservations
         WHERE status = 'Pending'"
    )
)['total'];

$approved = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM reservations
         WHERE status = 'Approved'"
    )
)['total'];

$completed = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM reservations
         WHERE status = 'Completed'"
    )
)['total'];

?>

<h2 class="mb-4">
    Dashboard
</h2>

<div class="row g-4">

    <div class="col-md-3">

        <div class="stat-card">

            <h6>Total Reservations</h6>

            <h2>
                <?= $total; ?>
            </h2>

        </div>

    </div>

    <div class="col-md-3">

        <div class="stat-card">

            <h6>Pending</h6>

            <h2>
                <?= $pending; ?>
            </h2>

        </div>

    </div>

    <div class="col-md-3">

        <div class="stat-card">

            <h6>Approved</h6>

            <h2>
                <?= $approved; ?>
            </h2>

        </div>

    </div>

    <div class="col-md-3">

        <div class="stat-card">

            <h6>Completed</h6>

            <h2>
                <?= $completed; ?>
            </h2>

        </div>

    </div>

</div>

<?php

include "includes/admin_footer.php";

?>