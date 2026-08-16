<?php

session_start();

unset($_SESSION['customer_id']);
unset($_SESSION['customer_name']);
unset($_SESSION['customer_role']);

session_destroy();

header("Location: ../login.php");

exit();

?>