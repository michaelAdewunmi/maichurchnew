<?php
require_once './config/config.php';
session_start();

if ($_SESSION['admin_type'] !== 'super' && $_SESSION['admin_type'] !== 'supercashr'
) {
    echo "
            <script type='text/javascript'>
                alert('Unauthorized to access this page');
                window.location='logout.php';
            </script>
        ";
}

if( !isset($_GET['gothedistance']) || !$_GET['gothedistance']) {
    header('location:index.php');
}
?>

<head>
    <link rel="stylesheet" href="assets/css/special-notification-and-extras.css">
</head>

<div class="special_div">
    <h1 class="notification">Day Ended Successfully</h1>
    <a class="special_btn" href="./logout.php">Log Out</a>
    <a class="special_btn" href="./index.php">Go Home</a>
</div>