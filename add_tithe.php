<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';
require_once './includes/send_sms.php';

//serve POST method, After successful insert, redirect to tithe_post.php page.
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

    function getReceiptNumber()

    {
        $db = getDbInstance();
        $db->where("CashierAssigned", $_SESSION['username']);
        $db->where("UsuageStatus", '1');
        $row = $db->get('receiptnumberpool');
        if ($db->count >=1) {
            $ReceiptStart = $row[0]['ReceiptNumber'];
            $ReceiptUsed =  $row[0]['UsedReceiptNumber'];


            $ReceiptNumber = ltrim($ReceiptStart, '0') + $ReceiptUsed;
            $NewReceiptNumbers = sprintf('%07d', $ReceiptNumber);
            $ReceiptUsedUpdate = $ReceiptUsed + 1;

            if ($ReceiptUsedUpdate == 150) {
                $db = getDbInstance();
                $db->where('CashierAssigned', $_SESSION['username']);
                $db->where('UsuageStatus', '1');

                $update_remember = array (
                    'UsedReceiptNumber'=> $ReceiptUsedUpdate,
                    'UsuageStatus'=> 2,
                    'UsuageStatusUpdatedDate'=>date('Y-m-d H:i:s')
                );
                $db->update("receiptnumberpool", $update_remember);
                $db->rawQuery("CALL NextReceiptUpdate('$ReceiptStart')");
                return $NewReceiptNumbers;
            } else {
                $db = getDbInstance();
                $db->where('CashierAssigned', $_SESSION['username']);
                $db->where('UsuageStatus', '1');

                $update_remember = array(
                    'UsedReceiptNumber'=> $ReceiptUsedUpdate
                    );
                $db->update("receiptnumberpool", $update_remember);

            }

            return $NewReceiptNumbers;
        } else {
            $_SESSION['failure'] = "Tithe Information not added due to Non-availability of Receipt Number ";
            header('location:add_tithe.php');
            exit();
        }
    }



    $GeneratedReceiptNumber = getReceiptNumber();

    if(!empty($GeneratedReceiptNumber))
    {
        $data_for_bal_table['invoicenum'] = $dat_to_store['invoicenum'] =  $GeneratedReceiptNumber;
        $map_den_to_db_table = array (
            '1kqty' => 'Dem1000', '5hqty' => 'Dem500', '2hchk' => 'Dem200', '1hqty' => 'Dem100', '50qty' => 'Dem50',
            '20qty' => 'Dem20', '10qty' => 'Dem10', '5qty' => 'Dem5'
        );
        $map_bal_den_to_db_table = array (
            '1kqty_bal' => 'balDenom1000', '5hqty_bal' => 'balDenom500', '2hchk_bal' => 'balDenom200',
            '1hqty_bal' => 'balDenom100', '50qty_bal' => 'balDenom50', '20qty_bal' => 'balDenom20',
            '10qty_bal' => 'balDenom10', '5qty_bal' => 'balDenom5'
        );

        if($_POST['paymode']=="Cash") {
            foreach ($map_den_to_db_table as $key => $value) {
                if (isset($_POST[$key])) {
                    $dat_to_store[$value] =$_POST[$key];
                }
            }
            unset($key); unset($value);
            foreach ($map_bal_den_to_db_table as $key => $value) {
                if (isset($_POST[$key])) {
                    $data_for_bal_table[$value] =$_POST[$key];
                }
            }
        }

        $data_for_bal_table['TransactionCardNumber'] = $dat_to_store['TransactionCardNumber'] = $_POST['cardno'];
        if (!empty($_POST['cbankname'])) {
            $data_for_bal_table['BankName'] =  $dat_to_store['BankName'] = $_POST['cbankname'];
        } else if (!empty($_POST['dbankname'])) {
            $data_for_bal_table['BankName'] =  $dat_to_store['BankName'] = $_POST['dbankname'];
        }
        $data_for_bal_table['ChequeNumber'] =  $dat_to_store['ChequeNumber'] = $_POST['chequenumber'];
        $data_for_bal_table['DirectTransactionDate'] =  $dat_to_store['DirectTransactionDate'] = $_POST['transact_date'];
        $data_for_bal_table['date_received'] =  $dat_to_store['date_received'] = date('Y-m-d H:i:s');
        $data_for_bal_table['recusername'] =  $dat_to_store['recusername'] = $_SESSION['username']; //<!--assign the user in the

        $db = getDbInstance();
        $last_id = $db->insert('denominationanalysis', $dat_to_store);

        //create another instance to store data into the balance denominations table
        $db = getDbInstance();
        $last_id = $db->insert('tithebalancedenominations', $data_for_bal_table);


        //$NewReceiptNumbers = GetReceiptNumber();
        //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
        //  $data_to_store = array_filter($_POST);
        $data_to_store['trans_id'] = $getTranID;
        $data_to_store['invoicenum'] =  $GeneratedReceiptNumber;
        $data_to_store['memid'] = $_POST['memb_id'];
        $data_to_store['Name_member'] = $_POST['memb_name'];
        $data_to_store['branch_name'] = $_POST['memb_branch'];
        $data_to_store['band_name'] = $_POST['memb_band'];
        $data_to_store['Amount_Paid'] = $_POST['actual-tithe'];
        $data_to_store['Payment_Type'] = 'Tithe';
        $data_to_store['payment_mode'] = $_POST['paymode'];
        $data_to_store['payment_description'] = "Tithe for ". $_POST['duration'];
        $data_to_store['date_received'] = date('Y-m-d H:i:s');
        $data_to_store['recusername'] = $_SESSION['username']; //<!--assign the user in the post-->

        $db = getDbInstance();

        $last_id = $db->insert('tb_payment', $data_to_store);


        if ($last_id) {
            //echo $last_id;
            //compose_sms_to_send($data_to_store);
            add_posted_tithe_info_to_log($data_to_store);

            // $db = getDbInstance();
            // $db->where("id", $last_id);
            // $row2 = $db->get('tb_payment');

            //$_SESSION['here'] = $row2;


            header('location: tithe_receipt.php?page=null&intv='.base64_encode($GeneratedReceiptNumber));
            exit();
        } else {
            echo 'insert failed: ' . $db->getLastError();
            exit();
        }
    }else {
        $_SESSION['no'] = "The Value is Empty";
    }
}

if(isset($_SESSION['no'])) {
    echo $_SESSION['no'];
}if(isset($_SESSION['here'])) {
    var_dump($_SESSION['here']);
}



function compose_sms_to_send($tithe_info) {

    $sms = "Hello " . $tithe_info['Name_member'] . "," .
            "\n\nYour tithe was successfully posted." .
            " Please Confirm the details below \n\n" .
            "\n\nTithe For the Month(s): " . str_replace("Tithe for ", "", $tithe_info['payment_description']).
            "\nAmount Received: " . $tithe_info['Amount_Paid'] .
            "\nReceived and posted on " . date('D, jS M Y');
            send_sms_to_phone('treasury_token', $_POST["memb_phone"], $sms);
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once 'includes/header.php';
?>

<div id="page-wrapper">
<div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Post Tithe</h2>
        </div>

</div>
<?php include('./includes/flash_messages.php') ?>
    <form class="form" action="" method="post"  id="tithe_form" enctype="multipart/form-data">
       <?php  include_once('./forms/tithe_post.php'); ?>
    </form>
</div>


<script src="assets/js/jquery1.11.1.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="assets/js/daterangepicker.js"></script>
<script src="assets/js/moment.min.js"></script>

<script src="assets/js/typeahead.min.js"></script>
<script src="assets/js/modepick.js"></script>
<script src="assets/js/demomination.js"></script>
<script src="assets/js/currencyvalue.js"></script>
<script src="assets/js/sum_up_values.js"></script>
<link href="assets/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="assets/js/datepicker.min.js"></script>
<script src="assets/js/member_split.js"></script>
<style>
input.datepicker-here {width: 100%;}
    </style>

              <script src="assets/js/i18n/datepicker.en.js"></script>
<link rel="stylesheet" href="assets/css/typeahead.css">
<link rel="stylesheet" type="text/css" href="assets/css/daterangepicker.css">
<script>
    $(document).ready(function(){
    $('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'search.php?key=%QUERY',
        limit : 50
    });
});
    </script>
<script type="text/javascript">

$(document).ready(function(){
   $("#tithe_form").validate({
       rules: {
            memb_id: {
                required: true,
                minlength: 3
            },
            memb_name: {
                required: true,
                minlength: 3
            },
            memb_band: {
                required: true,
                minlength: 3
            },
            memb_branch: {
                required: true,
                minlength: 2
            },
            paymode: {
                required: true,
                minlength: 1
            },
            amountpaid: {
                required: true,
                minlength: 2
            },
        }
    });
});
</script>
<script type="text/javascript">
document.getElementById('cashmode').style.display="none";
document.getElementById('cardmode').style.display="none";
document.getElementById('directmode').style.display="none";
document.getElementById('chequemode').style.display="none";

</script>


<?php include_once ('includes/footer.php'); ?>
*/