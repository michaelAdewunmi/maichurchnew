<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

$receipt_number = base64_decode(filter_input(INPUT_GET, 'trans_ref'));
//echo '<br>';
$cashier = base64_decode(filter_input(INPUT_GET, 'initiator'));
//echo '<br>';
//serve POST method, After successful insert, redirect to members.php page.

if ($_SERVER['REQUEST_METHOD'] === 'GET') 
        {
            $db = getDbInstance();
            $db->where('invoicenum', $receipt_number);
            $db->where('recusername', $cashier);
            $update_remember = array(
                'reversal_status'=> '0'
                );
           $db->update("tb_payment", $update_remember);

            
            $_SESSION['success'] = "Tithe Reversal Successfully Denied";
        header('location:reversal_transact_grid.php');
        	exit();
        }

        
       
       
        
    

   
    //
?>



