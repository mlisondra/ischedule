<?php include('../config/production.php'); ?>
<?php
unset($_SESSION['user']['logged_in']);
header("Location: ../login/");
?>