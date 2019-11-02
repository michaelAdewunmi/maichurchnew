<?php
require_once './config/config.php';
session_start();


$db = getDbInstance();
$db->where('id', $_SESSION['id']);
$active_admin_user = $db->getOne('admin_accounts');


//Delete Admin Successful. write and store activity in admin_activity db table
$active_admin_names = $active_admin_user['surname'] . ' ' . $active_admin_user['firstname'] . '(' . $active_admin_user['user_name'] . ')';
$write_activity = $active_admin_names . ' Logged Out on ' . date('D, M, d, Y h:i:s: A');

$user_data = array(
	'admin_id'     => $_SESSION['id'],
	'session_id'   => session_id(),
	'date'         => date('Y-m-d H:i:s'),
	'activity'     => $write_activity,
);
$returned_id = $db->insert('admin_activity', $user_data);

//Store the activity information in a log file
$file = 'logs/log-general.txt';
if($handle = fopen($file, 'a')) {
	fwrite($handle,
		"\n" .
		$write_activity .
		"\n==========================================================================================================" .
        "\n=========================================================================================================="
	);
	fclose($handle);
}

session_destroy();


if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
	clearAuthCookie();
}
header('Location:index.php');
exit;

 ?>