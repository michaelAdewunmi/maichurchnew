<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

require __DIR__ . '/escpos/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfiles\StarCapabilityProfile;
$invoicenum = base64_decode(filter_input(INPUT_GET, 'intv'));
$route = filter_input(INPUT_GET, 'route');
$db = getDbInstance();

	$db->where("invoicenum", $invoicenum);

	$row = $db->get('tb_payment');
    if ($db->count >= 1) {
        if($row[0]['reversal_status']== 1) {
            $invoiceID = $row[0]['invoicenum']."(RVSL)";
        }else
        {
            $invoiceID = $row[0]['invoicenum'];
        }

        $TranID = $row[0]['trans_id'];
        $PayerID = $row[0]['memid'];
        $Payer = $row[0]['Name_member'];
        $Branch = $row[0]['branch_name'];
        $Band = $row[0]['band_name'];
        $PayDescription = $row[0]['payment_description'];
        $PayMode = $row[0]['payment_mode'];
        $PayAmount = $row[0]['Amount_Paid'];
        $Cashier = $row[0]['recusername'];

for($i=1;$i<=2;$i++) {
/* Fill in your own connector here */
$connector = new WindowsPrintConnector("XP-80C");
$profile = StarCapabilityProfile::getInstance();

/* Information for the receipt */
$items = array(
    new item("Receipt Number: ".$invoiceID),
    new item("TransactionID: ".$TranID),
    new item("PayeeID: ".$PayerID),
    new item("Payee: ". $Payer),
    new item("Band: ". $Band),
    new item("Branch: ". $Branch),
    new item("Payment Description: ". $PayDescription),
    new item("Payment Mode: ". $PayMode),

);
//$subtotal = new item('Subtotal', '12.95');
$cahsier = new item('Cashier: '.$Cashier);
$total = new item('Amount Paid', $PayAmount, true);
/* Date is kept the same for testing */
// $date = date('l jS \of F Y h:i:s A');
$date = date("l jS \of F Y h:i:s A");

/* Start the printer */
//$logo = EscposImage::load("assets/img/logo.png", false);
$printer = new Printer($connector, $profile);

/* Print top logo */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
//$printer -> graphics($logo);

/* Name of shop */
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("CHERUBIM & SERAPHIM \n");
$printer -> text("MOVEMENT CHURCH\n");
$printer -> selectPrintMode();
$printer -> text("SURULERE DISTRICT HEADQUARTERS\n");
$printer -> selectPrintMode();
$printer -> text("KM 4, APAPA OSHODI EXPRESSWAY, P.O. BOX 4426\n");
$printer -> text("Lagos, Nigeria\n");
$printer -> text("Financial Services and Management Ministry\n");
$printer -> feed();

/* Title of receipt */

if($i == 1){
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(true);
$printer -> text("TITHE RECEIPT - CUSTOMER'S COPY\n");
//$printer -> setEmphasis(false);
//$printer -> feed();
}
else if($i==2)
{
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> setEmphasis(true);
    $printer->text(" ");
    $printer->text("TITHE RECEIPT - CASHIER'S COPY");
    //$printer -> setEmphasis(false);
    $printer -> feed();
}
//$printer -> setEmphasis(false);

/* Items */
//$connector -> write(Printer::GS.'L'.chr(0).chr(0));
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(true);
foreach ($items as $item) {
        $printer -> text($item);
}
//$printer -> setEmphasis(true);

$printer -> setEmphasis(false);
$printer -> feed();

/* Tax and total */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
$printer -> text($total);
$printer -> text($cahsier);
$printer -> selectPrintMode();

/* Footer */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);

$printer -> text("Thank you for your Payment\n");
$printer -> feed(2);
$printer -> text($date . "\n");

/* Cut the receipt and open the cash drawer */
$printer -> cut();
$printer -> pulse();

$printer -> close();
    }
}
/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '=N= ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}

if($route == 'search' ):
   header('location: reprint_tithe.php');
else:
    $_SESSION['success'] = "Tithe Information added successfully!";
    header('location: add_tithe.php');
endif;
?>