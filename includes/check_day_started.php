<?php
/**
 * Check if day has been started by supercashr
 */
if ($_SESSION['admin_type'] !== "supercashr" AND $_SESSION['admin_type'] !== "super") {

    // $db = getDbInstance();
    // $db->where("day", date('Y-m-d'));

    // $row = $db->get('start_and_end_day_controller');

    // if ($db->count<1) {
    //     header('Location: day_not_started.php');
    // } else {

    //     $db = getDbInstance();
    //     $db->where("day", date('Y-m-d'));
    //     $db->where("day_started", false);

    //     $row = $db->get('start_and_end_day_controller');

    //     if ($db->count>=1) {
    //         //echo "yeah";
    //         header('Location: day_not_started.php');
    //     } else {
    //         $db = getDbInstance();
    //         $db->where("day", date('Y-m-d'));
    //         $db->where("day_ended", true);

    //         $row = $db->get('start_and_end_day_controller');
    //         if ($db->count>=1) {
    //             header('Location: day_not_started.php');
    //         }
    //     }
    // }

}