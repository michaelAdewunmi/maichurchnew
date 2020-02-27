<?php
require_once './config/config.php';
session_start();

if ($_SESSION['admin_type'] !== 'super' && $_SESSION['admin_type'] !== 'supercashr'
    && $_SESSION['admin_type'] !== 'cashier'
) {
    echo "
            <script type='text/javascript'>
                alert('You are not authorized to access this page');
                window.location='logout.php';
            </script>
        ";
}

if (isset($_GET['gothedistance']) && ($_GET['gothedistance']=="true" || $_GET['gothedistance']=="alreadydone")) {
?>

<head>
    <link rel="stylesheet" href="assets/css/special-notification-and-extras.css">
</head>

<div class="special_div">
    <h1 class="notification">Day Ended Successfully</h1>
    <div id="btns-holder">
        <a class="special_btn" href="./cash_analysis.php">View Cash Analysis</a>
    </div>
</div>

<?php
} else {
    header('location:index.php');
}
?>
