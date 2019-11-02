<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';
require_once './includes/send_sms.php';


$receipt_number = base64_decode(filter_input(INPUT_GET, 'trans_ref'));
//echo '<br>';
$cashier = base64_decode(filter_input(INPUT_GET, 'initiator'));
//echo '<br>';
//serve POST method, After successful insert, redirect to members.php page.

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    function getReceiptNumber() {
        $cashier = base64_decode(filter_input(INPUT_GET, 'initiator'));
        $db = getDbInstance();
        $db->where("CashierAssigned", $cashier);
        $db->where("UsuageStatus", '1');
        $row = $db->get('receiptnumberpool');
        if ($db->count >=1) {
            $ReceiptStart = $row[0]['ReceiptNumber'];
            $ReceiptUsed =  $row[0]['UsedReceiptNumber'];


            $ReceiptNumber = ltrim($ReceiptStart, '0') + $ReceiptUsed;
            $NewReceiptNumbers = sprintf('%07d', $ReceiptNumber);
            $ReceiptUsedUpdate = $ReceiptUsed + 1;

            if($ReceiptUsedUpdate == 150)
            {

                $db = getDbInstance();
                $db->where('CashierAssigned', $_SESSION['username']);
                $db->where('UsuageStatus', '1');

                $update_remember = array(
                    'UsedReceiptNumber'=> $ReceiptUsedUpdate,
                    'UsuageStatus'=> 2,
                    'UsuageStatusUpdatedDate'=>date('Y-m-d H:i:s')
                    );
                $db->update("receiptnumberpool", $update_remember);

                            $db->rawQuery("CALL NextReceiptUpdate('$ReceiptStart')");

                return $NewReceiptNumbers;

            }
            else
            {
                $db = getDbInstance();
                $db->where('CashierAssigned', $_SESSION['username']);
                $db->where('UsuageStatus', '1');

                $update_remember = array(
                    'UsedReceiptNumber'=> $ReceiptUsedUpdate
                    );
                $db->update("receiptnumberpool", $update_remember);

            }

            return $NewReceiptNumbers;
        }
        else
        {
            $_SESSION['failure'] = "Tithe Reversal not Approved due to Non-availability of Receipt Number ";
            header('location:reversal_transact_grid.php');

            exit();
        }
    }


    $GeneratedReceiptNumber = GetReceiptNumber();
    $db = getDbInstance();
    $db->rawQuery("CALL UpdateReversal('$receipt_number','$cashier','$GeneratedReceiptNumber')");

    /*
    $db = getDbInstance();
    $db->where('invoicenum', $receipt_number);
    $db->where('recusername', $cashier);
    $update_remember = array(
        'reversal_status'=> '2',
        'reversedreceiptnumber'=>$GeneratedReceiptNumber
        );
    $db->update("tb_payment", $update_remember);
    $db->update("reversedtransactions", $update_remember);


    $db1 = getDbInstance();
    $db1->where('reversedreceiptnumber', $receipt_number);
    $db1->where('recusername', $cashier);
    //$db1->orwhere('PostedBy', $cashier);
    $update_remember = array(
    //'reversal_status'=> '2',
    'invoicenum'=>$GeneratedReceiptNumber
    );
    $update_remembe = array(
            'InvoiceNum'=>$GeneratedReceiptNumber
        );
    $db->update("tb_payment_reversed_tmp", $update_remember );
    $db1->update("denominationanalysis_reversed_tmp", $update_remembe); */
    $_SESSION['success'] = "Tithe Reversal Successfully Approved";

    $db = getDbInstance();
    $db->where('invoicenum', $receipt_number);
    $row1 = $db->get('tb_payment');

    $old_input = end($row1);
    $db = getDbInstance();
    $db->where('invoicenum', $old_input['reversedreceiptnumber']);
    $row2 = $db->get('tb_payment');
    $new_input = end($row2);

    compose_sms_to_send($old_input, $new_input);
    add_posted_tithe_info_to_log(array($old_input, $new_input), "Reversal Approved");
    header('location:reversal_transact_grid.php');
    exit();
}


function compose_sms_to_send($old_tithe_info, $new_tithe_info) {
    $db = getDbInstance();
    $db->where('memberid', $new_tithe_info['memid']);
    $member = $db->get('tb_personinfo');

    if($db->count>0) {
        $tither_number = $member[0]['mobile_no'];

        if($old_tithe_info['memid']!==$new_tithe_info['memid']) {
            send_sms_to_wrong_member($old_tithe_info['memid'], $old_tithe_info['Name_member']);
            $reversal_reason = 'Payment into Wrong Account';
        } else if(abs($old_tithe_info['Amount_Paid'])!==abs($new_tithe_info['Amount_Paid'])) {
            $reversal_reason = 'Wrong Amount Details';
        } else if($old_tithe_info['payment_description']!==$new_tithe_info['payment_description']) {
            $reversal_reason = 'Wrong Tithe Month(s)';
        }
        $sms = "Hello " . $new_tithe_info['Name_member'] . "," .
        "\n\nA Reversal has been done, and correction has been made on your recently paid tithe." .
        "\nREASON FOR REVERSAL: " . $reversal_reason .
        "\n\nThe CORRECT TITHE DETAILS are as shown below\n" .
        "Tither Name: " . $new_tithe_info['Name_member'] . "\n" .
        "Tithe For the Month(s): " . str_replace("Tithe for ", "", $new_tithe_info['payment_description'])  . "\n" .
        "Amount Received: " . $new_tithe_info['Amount_Paid'] . "\n" .
        "Received and posted on " . $new_tithe_info['date_received'] .
        "\n\nSHALLOM!\nSURULERE TREASURY UNIT";
        send_sms_to_phone('treasury_token', $tither_number, $sms);
    }
}


function send_sms_to_wrong_member($member_id, $wrong_member_name) {
    $db = getDbInstance();
    $db->where('memberid', $member_id);
    $wrong_member = $db->get('tb_personinfo');

    if($db->count>0) {
        $sms = "Hello ". $wrong_member_name .
        ", This is to notify you that a Tithe was wrongly logged".
        " into your account but has been successfully reversed.".
        "\n\nsHALLOM!\nSURULERE TREASURY UNIT";
        send_sms_to_phone('treasury_token', $wrong_member[0]['mobile_no'], $sms);
    }
}
?>



