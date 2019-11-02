<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$del_id = filter_input(INPUT_POST, 'del_id');
 $db = getDbInstance();

if($_SESSION['admin_type']!='super'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
}


// Delete a user using user_id
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $db->where('id', $del_id);
    $user_to_delete = $db->getOne('admin_accounts'); //get the user info before deleting so as to save activity to the admin_activity table

    //Now Go ahead and delete;
    $db->where('id', $del_id);
    $stat = $db->delete('admin_accounts');
    if ($stat) {
        $_SESSION['info'] = "User deleted successfully!";

        //Write accomplished task to a log file and the database. Function definition found in helpers.php
        save_general_admin_activity_to_log($data_to_store, "admin", "delete", null, $user_to_delete);

        // $db->where('id', $_SESSION['id']);
        // $active_admin_user = $db->getOne("admin_accounts");

        // //Delete Admin Successful. write and store activity in admin_activity db table
        // $active_admin_names = $active_admin_user['surname'] . ' ' . $active_admin_user['firstname'] . '(' . $active_admin_user['user_name'] . ')';

        // $deleted_user_info = $user_to_delete['surname'] . ' ' . $user_to_delete['firstname'];

        // $write_activity = $active_admin_names . ' deleted an Admin (' . $deleted_user_info . ')  with the username ' . $user_to_delete['user_name'] .  ' on ' . date('D, M, d, Y h:i:s: A');

        // $_SESSION['delete-admin_activity'] = $write_activity;

        // $user_data = array(
        //     'admin_id'     => $_SESSION['id'],
        //     'session_id'   => session_id(),
        //     'date'         => date('Y-m-d H:i:s'),
        //     'activity'     => $_SESSION['delete-admin_activity'],

        // );
        // $returned_id = $db->insert('admin_activity', $user_data);

        // //Store the activity information in a log file
        // $file = 'logs/log-general.txt';
        // if($handle = fopen($file, 'a')) {
        //     fwrite($handle, "\n" . $_SESSION['delete-admin_activity']);
        //     fclose($handle);
        // }
        header('location: admin_users.php');
        exit;
    }
}