<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

        function generateTranID()

    {

                $regid = 'TRN'.''.mt_rand(2000000, 9999999);
                return $regid;

    }
    $getTranID = generateTranID();
    //echo $getappnum;



    function checkTranID($getregid)

    {
        $db = getDbInstance();
        //$db->where("CashierAssigned", $cashier);
        $db->where("trans_id", $getTranID);
        $row = $db->get('tb_payment');
        if ($db->count >=1) {
            generateTranID();
        }
        else {

            return $getTranID;
        }


    }

    //$dat_to_store['invoicenum'] =  $GeneratedReceiptNumber;

    if(isset($_POST['1kqty'])){
        $dat_to_store['Dem1000'] = $_POST['1kqty'];
    }
    if(isset($_POST['5hqty']))
    {
        $dat_to_store['Dem500'] = $_POST['5hqty'];
    }

    if(isset($_POST['2hchk']))
    {
        $dat_to_store['Dem200'] = $_POST['2hchk'];
    }

    if(isset($_POST['1hqty']))
    {
        $dat_to_store['Dem100'] = $_POST['1hqty'];
    }

    if(isset($_POST['50qty']))
    {
        $dat_to_store['Dem50'] = $_POST['50qty'];
    }

    if(isset($_POST['20qty']))
    {
        $dat_to_store['Dem20'] = $_POST['20qty'];
    }
    //Insert timestamp

    if(isset($_POST['10qty'])) {
        $dat_to_store['Dem10'] = $_POST['10qty'];
    }

    if(isset($_POST['5qty'])) {
        $dat_to_store['Dem5'] = $_POST['5qty'];
    }




    $dat_to_store['reversedreceiptnumber'] =  $_POST['receiptnum_hid'];
    $dat_to_store['TransactionCardNumber'] = $_POST['cardno'];
    $dat_to_store['date_received'] = date('Y-m-d H:i:s');
    $dat_to_store['recusername'] = $_SESSION['username']; //<!--assign the user in the

    $db = getDbInstance();

    $last_id = $db->insert('denominationanalysis_reversed_tmp', $dat_to_store);
    //$NewReceiptNumbers = GetReceiptNumber();
    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
  //  $data_to_store = array_filter($_POST);
    $data_to_store['trans_id'] = $getTranID;
    //Insert timestamp
    if(!isset($_POST['duration']))
    {
        $data_to_store['payment_description'] = "Tithe for ". $_POST['duration_hid'];
    }
    else{
        $data_to_store['payment_description'] = "Tithe for ". $_POST['duration'];
    }
    $data_to_store['reversal_purpose'] =  $_POST['reversal_opts'];
    $data_to_store['reversedreceiptnumber'] =  $_POST['receiptnum_hid'];
    $data_to_store['memid'] = $_POST['memb_id'];
    $data_to_store['Name_member'] = $_POST['memb_name'];
    $data_to_store['branch_name'] = $_POST['memb_branch'];
    $data_to_store['band_name'] = $_POST['memb_band'];
    $data_to_store['Amount_Paid'] = $_POST['amountpaid'];
    $data_to_store['Payment_Type'] = 'Tithe';
if(isset($_POST['paymode'])){
    $data_to_store['payment_mode'] = $_POST['paymode'];
}
    $data_to_store['reversal_status'] = 1;
    $data_to_store['date_received'] = date('Y-m-d H:i:s');
    $data_to_store['recusername'] = $_SESSION['username']; //<!--assign the user in the post-->

    $db = getDbInstance();

    $last_id = $db->insert('tb_payment_reversed_tmp', $data_to_store);

    $invoicenum =  $_POST['receiptnum_hid'];   // return $NewReceiptNumbers;
    $db = getDbInstance();
    $db->where('invoicenum', $invoicenum);
    $db->where('recusername', $_SESSION['username']);
    $update_remember = array(
        'reversal_status'=> '1'
        );
   $update_trans =  $db->update("tb_payment", $update_remember);

   if($update_trans)
{
    echo 'Update Done';

}
else
{
    echo 'update  failed: ' . $db->getLastError();
}

    if($last_id)
    {
        $_SESSION['success'] = "Tithe Reversal Information added successfully!";
        add_posted_tithe_info_to_log($invoicenum, true);
    	header('location:reverse_tithe.php');
    	exit();
    }
    else
    {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}
?>



