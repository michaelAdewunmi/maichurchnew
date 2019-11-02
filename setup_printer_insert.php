<?php

session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';



$db = getDbInstance();
$dat_to_store['PrinterName'] = $_POST['printer_name'];
$dat_to_store['PrinterModel'] = $_POST['printer_model'];
$dat_to_store['AssignedTo'] = $_POST['cashier'];
$dat_to_store['AssignedDate'] = date('Y-m-d H:i:s');
$dat_to_store['AssignedBy'] = $_SESSION['username'];


$last_id = $db->insert('printer_assign', $dat_to_store);

if($last_id)
{
    $_SESSION['success'] = "Printer Information added successfully!";
    header('location: printer_setup.php');
    exit();
}
else
{
    echo 'insert failed: ' . $db->getLastError();
    exit();
}

?>