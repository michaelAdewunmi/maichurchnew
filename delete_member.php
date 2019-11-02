<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';
$del_id = filter_input(INPUT_POST, 'del_id');
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST')
{

	if($_SESSION['admin_type']!='super'){
		$_SESSION['failure'] = "You don't have permission to perform this action";
    	header('location: members.php');
        exit;

	}
    $member_id = $del_id;

    $db = getDbInstance();
    $db->where('memberid', $member_id);
    $deleted_member = $db->getOne('tb_personinfo'); //get the member info before deleting so as to save info to a log file
    $db->where('memberid', $member_id);
    $status = $db->delete('tb_personinfo');

    if ($status)
    {
        $_SESSION['info'] = "member deleted successfully!";


        //Write accomplished task to a log file and the database. Function definition found in helpers.php
        save_general_admin_activity_to_log($data_to_store, "member", "delete", $deleted_member);

        header('location: members.php');
        exit;
    }
    else
    {
    	$_SESSION['failure'] = "Unable to delete member";
    	header('location: members.php');
        exit;

    }

}