<?php

session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';



$db = getDbInstance();
$dat_to_store['ReceiptNumber'] = $_POST['receiptnumb'];
$dat_to_store['ReceiptNumberRange'] = $_POST['leafletnumb'];
$dat_to_store['CashierAssigned'] = $_POST['cashier'];
$dat_to_store['AssignedDate'] = date('Y-m-d H:i:s');
$dat_to_store['AssignedBy'] = $_SESSION['username'];


$last_id = $db->insert('receiptnumberpool', $dat_to_store);

if($last_id)
{
    $_SESSION['success'] = "Receipt Information added successfully!";
    header('location: assign_receipt_book.php');
    exit();
}
else
{
    echo 'insert failed: ' . $db->getLastError();
    exit();
}

?>