<?php
require_once './config/config.php';
session_start();

if ($_SESSION['admin_type'] !== 'super'
    && $_SESSION['admin_type'] !== 'supercashr'
) {
    echo "
            <script type='text/javascript'>
                alert('Unauthorized to access this page');
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
$db->where("day_ended", date(true));

$row = $db->get('start_and_end_day_controller');
if ($db->count >=1) {
    echo $db->count;
    header('location:day_end_successful.php?gothedistance=alreadydone');
} else {
    $db = getDbInstance();
    $data = Array (
        "day"               => date('Y-m-d'),
        "day_started"       => true,
        "time_day_started"  => 'not_assigned',
        "day_ended"         => true,
        "time_day_ended"    => date('Y-m-d H:i:s'),
        "endorsed_by"       => "supercashr",
    );
    $result = $db->insert("start_and_end_day_controller", $data);

    if($result) {
        header('location:day_end_successful.php?gothedistance=true');
    }
}

