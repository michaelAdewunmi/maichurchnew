<?php
require_once './config/config.php';
session_start();

if ($_SESSION['admin_type'] !== 'super'
    && $_SESSION['admin_type'] !== 'supercashr' && $_SESSION['admin_type'] !== 'cashier'
) {
    echo "
            <script type='text/javascript'>
                alert('You are Unauthorized to access this page');
                window.location='logout.php';
            </script>
        ";
}
?>

<head>
    <link rel="stylesheet" href="assets/css/special-notification-and-extras.css">
</head>

<?php

$db = getDbInstance();
$db->where("day", date('Y-m-d'));
$db->where("day_started_for", $_SESSION['username']);
$db->where("day_ended", true);

$row = $db->get('start_and_end_day_controller');
if ($db->count >0) {
    echo $db->count;
    header('location:day_end_successful.php?gothedistance=alreadydone');
} else {
    $db = getDbInstance();
    $db->where("day", date('Y-m-d'));
    $db->where("day_started_for", $_SESSION['username']);
    $db->where("day_ended", "NOT YET");
    $data = Array (
        "day_ended"         => true,
        "day_ended_for"     => $_SESSION['username'],
        "time_day_ended"    => date('Y-m-d H:i:s'),
    );
    $result = $db->update("start_and_end_day_controller", $data);
    if ($result) {
        header('location:day_end_successful.php?gothedistance=true');
    } else {
        header('location:problem_page.php');
    }
}

