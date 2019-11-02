<?php
/**
 * The Variables below are being used to insert denominations inputs into the DOM
 * used for amount collected and balance
 */
$mapQntToMultipliers = array(
  '1kqty'=>'1kttl', '5hqty'=>'5httl', '2hqty'=>'2httl', '1hqty'=>'1httl',
  '50qty'=>'50ttl', '20qty'=>'20ttl', '10qty'=>'10ttl',
  '5qty'=>'5ttl'
);
$mapCheckboxToLabel = array(
  '1kqty'=>'1kchk', '5hqty'=>'5hchk', '2hqty'=>'2hchk', '1hqty'=>'1hchk',
  '50qty'=>'50chk', '20qty'=>'20hck', '10qty'=>'10hck',
  '5qty'=>'5chk'
);
$mapCheckboxToAmnt = array(
  '1kqty'=>'1000.00', '5hqty'=>'500.00', '2hqty'=>'200.00', '1hqty'=>'100.00',
  '50qty'=>'50.00', '20qty'=>'20.00', '10qty'=>'10.00', '5qty'=>'5.00'
);
$qnt = array('1kqty'=>'1kqty_bal', '5hqty'=>'5hqty_bal', '2hqty'=>'2hqty_bal', '1hqty'=>'1hqty_bal', '50qty'=>'50qty_bal',
'20qty'=>'20qty_bal', '10qty'=>'10qty_bal', '5qty'=>'5qty_bal');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

    $dbget = getDbInstance();
    $optionget = $dbget->get("reversal_options");


    $invoicenum = filter_input(INPUT_POST, 'reverse_id');
    $db = getDbInstance();
    $db->where('invoicenum', $invoicenum);
    $db->where('recusername', $_SESSION['username']);
    $row = $db->get("tb_payment");
}
?>
<div class="well text-left filter-form">
  <form class="form form-inline" action="">
    <div class="form-group">
        <label>Reason for reversal *</label>

            <select name="reversal_options" id="reversal_options" class="form-control selectpicker" onChange="reversal_option(this.value)" required>
                <option value=" ">Please select reason</option>
                <?php

                foreach ($optionget as $optiongetvalue) {
                  echo ' <option value="' . $optiongetvalue['OptionName'] . '">' . $optiongetvalue['OptionName'] . '</option>';
                }
                ?>
            </select>
    </div>
    <fieldset>
      <div class="form-group">
          <label for="memb_search">Search Member *</label>
          <input type="text" name="typeahead" id="typeahead" class="typeahead tt-query"
              autocomplete="off" spellcheck="false" placeholder="Type your Search Query"
              onclick="member_split()" autofocus />
      </div>
      <br>
      <hr class="style13">
      <div class="form-group">
        <label for="memb_id">Member ID:</label>
        <input type="text" class="form-control" id="memb_id" name="memb_id"
          value="<?php echo htmlspecialchars($row[0]['memid'])?>" readonly />
      </div>
      <div class="form-group">
        <label for="memb_name">Member Name:</label>
        <input type="text" class="form-control" id="memb_name" name="memb_name"
          value="<?php echo htmlspecialchars($row[0]['Name_member'])?>" readonly />
      </div>
      <div class="form-group">
        <label for="memb_band">Member Band:</label>
        <input type="text" class="form-control" id="memb_band" name="memb_band"
          value="<?php echo htmlspecialchars($row[0]['band_name'])?>" readonly />
      </div>
      <div class="form-group">
        <label for="memb_band">Member Branch:</label>
        <input type="text" class="form-control" id="memb_branch" name="memb_branch"
          value="<?php echo htmlspecialchars($row[0]['branch_name'])?>" readonly />
      </div>
      <input type="hidden" name="duration_hid" id = "duration_hid"
        value="<?php echo substr(htmlspecialchars($row[0]['payment_description']), 10) ?>" />
      <input type="hidden" name="receiptnum_hid" id = "receiptnum_hid"
        value="<?php echo htmlspecialchars($row[0]['invoicenum']) ?>" />
      <input type="hidden" name="reversal_opts" id = "reversal_opts" />
      <div class="form-group ">
        <label class="control-label " for="calendar">Tithe Period</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
          <input id ="duration" name="duration" autocomplete="off" type="text"
            value="<?php echo substr(htmlspecialchars($row[0]['payment_description']), 10)?>"
            class="datepicker-here" data-language='en' data-min-view="months" data-view="months"
            data-date-format="MM yyyy" data-multiple-dates="999" data-multiple-dates-separator=", " required />
        </div>
        <div class="form-group">
          <div class="input-group">
          <label class="control-label " for="paymode">Mode of Payment</label>
  <select class="form-control" id="paymode" name="paymode" onchange="select_mode(this.value)" disabled>
  <option></option>
    <option>Cash</option>
    <option>Card</option>
    <option>Cheque</option>
    <option>Direct Lodgement</option>
   </select>
   </select>
  </div>
 </div>

  <div id="cashmode">

    <?php
        /**
         * NOTE: The Variable used there has been decalared at the top of this file
         * as it is also used by the balance section.
         */
      foreach ($mapQntToMultipliers as $key => $val) {
        $cbid = $mapCheckboxToLabel[$key];
        $amnt = $mapCheckboxToAmnt[$key];
    ?>
      <!--  Please do not change the order of Divs arrangement in the section below.
          If You do, it will cause things to break cos javascript is making use of the arrangement.
          If the Order needs to change, then currencyvalues.js needs to change too.
      -->
      <div class="custom-control custom-checkbox mb-3 form-inline">
        <input type="checkbox" class="custom-control-input"
          id="<?php echo $cbid; ?>" name="<?php echo $cbid; ?>"
            onchange="doMath.focusAmountInput(this)" />
        <label class="custom-control-label amnt" for="<?php echo $cbid; ?>">N<?php echo $amnt; ?></label>
        <label class="mr-sm-3" for="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input class="form-control amnt-input" id="<?php echo $key; ?>" name="<?php echo $key; ?>"
          type="number" value="" onfocus="doMath.multplyDenByNumb(this)" disabled />
        <input class="form-control" id="<?php echo $val; ?>" name="<?php echo $val; ?>"
          type="text" value="" readonly />
      </div>
      <hr class="style13">
      <?php } ?>
<!--
    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="1kchk" name="1kchk" onchange="k1deno()">
      <label class="custom-control-label" for="1kchk">N1,000.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;</label>
      <input class="form-control" id="1kqty" name="1kqty" type="number" value="" onchange="k1convert(this.value)" disabled>
      <input class="form-control" id="1kttl" name="1kttl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">
    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="5hchk" name="5hchk" onchange="h5deno()">
      <label class="custom-control-label" for="1kchk">N500.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="5hqty" name="5hqty" type="number" value="" onchange="h5convert(this.value)" disabled>
      <input class="form-control" id="5httl" name="5httl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="2hchk" name="2hchk" onchange="h2deno()">
      <label class="custom-control-label" for="1kchk">N200.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="2hqty" name="2hqty" type="number" value="" onchange="h2convert(this.value)" disabled>
      <input class="form-control" id="2httl" name="2httl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="1hchk" name="1hchk" onchange="h1deno()">
      <label class="custom-control-label" for="1kchk">N100.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="1hqty" name="1hqty" type="number" value="" onchange="h1convert(this.value)" disabled>
      <input class="form-control" id="1httl" name="1httl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="50chk" name="50chk" onchange="n50deno()">
      <label class="custom-control-label" for="50chk">N50.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="50qty" name="50qty" type="number" value="" onchange="n50convert(this.value)" disabled>
      <input class="form-control" id="50ttl" name="50ttl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="20chk" name="20chk" onchange="n20deno()">
      <label class="custom-control-label" for="50chk">N20.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="20qty" name="20qty" type="number" value="" onchange="n20convert(this.value)" disabled>
      <input class="form-control" id="20ttl" name="20ttl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="10chk" name="10chk" onchange="n10deno()">
      <label class="custom-control-label" for="50chk">N10.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="10qty" name="10qty" type="number" value="" onchange="n10convert(this.value)" disabled>
      <input class="form-control" id="10ttl" name="10ttl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13">

    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input" id="5chk" name="5chk" onchange="n5deno()">
      <label class="custom-control-label" for="50chk">N5.00</label>
      <label class="mr-sm-3" for="paymode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control" id="5qty" name="5qty" type="number" value="" onchange="n5convert(this.value)" disabled>
      <input class="form-control" id="5ttl" name="5ttl" type="text" value="" onfocus="sum_up_values()" readonly>
    </div>
    <hr class="style13"> -->

  </div>

<div id="cardmode">

<div class="form-group">
  <label class="control-label " for="cardno">
   Enter Last Four(4) digits of the Card
  </label>
  <div class="input-group">
   <div class="input-group-addon">
	<i class="fa fa-credit-card"></i>
   </div>
   <input class="form-control" id="cardno" name="cardno" type="text" value="" maxlength="4">
  </div>
 </div>

</div>

<div id="chequemode">
<div class="form-group col-sm-3">
        <label>Bank</label>
        <?php //$opt_arr = get_countries();
           $db = getDbInstance();
           $db->orderBy('BankName','asc');
           $select = "BankName";
           $opt_arr = $db->get('banks_tbl', null, $select);
                            ?>
            <select name="cbankname" id="cbankname" class="form-control" required>
                <option value="">Select Bank</option>
                <?php
                foreach ($opt_arr as $opt) {

                    echo '<option value="'.$opt['BankName'].'">' . $opt['BankName'] . '</option>';
                }

                ?>
            </select>
    </div>
    <div class="form-group">
  <label class="control-label " for="chequenumber">
   Enter Bank Cheque Number
  </label>
  <div class="input-group col-sm-3">
   <div class="input-group-addon">
	<i class="fa fa-bank"></i>
   </div>
   <input class="form-control" id="chequenumber" name="chequenumber" type="text" value="" maxlength="12">
  </div>
 </div>
</div>
<div id="directmode">
<div class="form-group col-sm-3">
        <label>Bank</label>
        <?php //$opt_arr = get_countries();
           $db = getDbInstance();
           $db->orderBy('BankName','asc');
           $select = "BankName";
           $opt_arr = $db->get('banks_tbl', null, $select);
                            ?>
            <select name="dbankname" id="dbankname" class="form-control selectpicker" required>
                <option value="">Select Bank</option>
                <?php
                foreach ($opt_arr as $opt) {

                  echo '<option value="'.$opt['BankName'].'">' . $opt['BankName'] . '</option>';
                }

                ?>
            </select>
    </div>

  <div class="form-group">
  <label class="control-label " for="transact_date">
   Transaction Date
  </label>
  <div class="input-group col-sm-2">
   <div class="input-group-addon">
	<i class="fa fa-calendar"></i>
   </div>
   <input class="form-control" id="transact_date" name="transact_date" type="date" value="">
  </div>
 </div>

</div>

 <div class="form-group">
  <label class="control-label " for="amountpaid">
   Amount Paid
  </label>
  <div class="input-group">
   <div class="input-group-addon">
	<i class="fa fa-money"></i>
   </div>
   <input class="form-control" id="amountpaid" name="amountpaid" type="text" value="<?php echo htmlspecialchars($row[0]['Amount_Paid'])?>" readonly>
  </div>
 </div>


 <button type="submit" id="reversalbtn" class="btn btn-primary mb-2"><i class='fa fa-undo'>&nbsp;</i>Reverse Transaction</button>
</fieldset>




 </form>
 </div>



