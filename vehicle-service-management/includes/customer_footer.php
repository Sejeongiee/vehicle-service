</main>

<footer class="customer-footer">

    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <h5>
                    🚗 Vehicle Service Management
                </h5>

                <p>
                    Reliable vehicle service management
                    for your vehicle maintenance needs.
                </p>

            </div>

            <div class="col-md-3">

                <h6>
                    Quick Links
                </h6>

                <a href="/vehicle-service-management/">
                    Home
                </a>

                <a href="/vehicle-service-management/services.php">
                    Services
                </a>

                <a href="/vehicle-service-management/contact.php">
                    Contact
                </a>

            </div>

            <div class="col-md-3">

                <h6>
                    Account
                </h6>

                <?php if (isset($_SESSION['customer_id'])): ?>

                    <a href="/vehicle-service-management/customer/dashboard.php">
                        Dashboard
                    </a>

                    <a href="/vehicle-service-management/customer/profile.php">
                        Profile
                    </a>

                    <a href="/vehicle-service-management/customer/logout.php">
                        Logout
                    </a>

                <?php else: ?>

                    <a href="/vehicle-service-management/login.php">
                        Login
                    </a>

                    <a href="/vehicle-service-management/register.php">
                        Register
                    </a>

                <?php endif; ?>

            </div>

        </div>

        <hr>

        <div class="text-center">

            <small>
                © 2026 Vehicle Service Management.
                All rights reserved.
            </small>

        </div>

    </div>

</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script
    src="/vehicle-service-management/js/customer.js">
</script>

</body>

</html>