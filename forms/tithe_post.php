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
?>
<fieldset>
  <div class="form-group">
    <label for="memb_search">Search Member *</label>
    <input type="text" name="typeahead" id="typeahead" class="typeahead tt-query"
      autocomplete="off" spellcheck="false" placeholder="Type your Search Query"
      onblur="member_split()" autofocus />
  </div>
  <br />
  <hr class="style13" />
  <div class="form-group col-lg-2">
    <label for="memb_id">Member ID:</label>
    <input type="text" class="form-control" id="memb_id" name="memb_id" readonly />
  </div>
  <div class="form-group col-lg-6">
    <label for="memb_name">Member Name:</label>
    <input type="text" class="form-control" id="memb_name" name="memb_name" readonly />
  </div>
  <div class="form-group col-lg-4">
    <label for="memb_band">Member Band:</label>
    <input type="text" class="form-control" id="memb_band" name="memb_band" readonly />
  </div>
  <div class="form-group">
    <label for="memb_band">Member Branch:</label>
    <input type="text" class="form-control" id="memb_branch" name="memb_branch" readonly />
  </div>
  <input type="hidden" class="form-control" id="memb_phone" name="memb_phone" readonly />
  <div class="form-group">
    <label class="control-label " for="calendar">Tithe Period</label>
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
      <input id ="duration" name="duration" type="text" autocomplete="off"
          class="datepicker-here"
          data-language='en'
          data-min-view="months"
          data-view="months"
          data-date-format="MM yyyy"
          data-multiple-dates="100"
          data-multiple-dates-separator=", " required
      />
    </div>
    <div class="form-group">
      <label class="control-label " for="paymode">Mode of Payment</label>
      <select class="form-control" id="paymode"
        name="paymode" onchange="select_mode(this.value)">
        <option></option>
        <option>POS</option>
        <option>Card</option>
        <option>Cheque</option>
        <option>Direct Lodgement</option>
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
  </div>
  <div id="cardmode">
    <div class="form-group">
      <label class="control-label " for="cardno">Enter Last Four(4) digits of the Card</label>
      <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
        <input class="form-control" id="cardno" name="cardno" type="text" value="" autocomplete="off" maxlength="4">
      </div>
    </div>
  </div>
  <div id="chequemode">
    <div class="form-group col-sm-3">
      <label>Bank</label>
      <?php //$opt_arr = get_countries();
        $db = getDbInstance();
        $db->orderBy('BankName', 'asc');
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
      <label class="control-label " for="chequenumber">Enter Bank Cheque Number</label>
      <div class="input-group col-sm-3">
        <div class="input-group-addon"><i class="fa fa-bank"></i></div>
        <input class="form-control" id="chequenumber" name="chequenumber" type="text" value="" maxlength="12" />
      </div>
    </div>
  </div>
  <div id="directmode">
    <div class="form-group col-sm-3">
      <label>Bank</label>
      <?php //$opt_arr = get_countries();
        $db = getDbInstance();
        $db->orderBy('BankName', 'asc');
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
      <label class="control-label " for="transact_date">Transaction Date</label>
      <div class="input-group col-sm-2">
        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input class="form-control" id="transact_date" name="transact_date" type="date" value="" />
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label " for="amountpaid">Amount Paid</label>
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-money"></i></div>
      <input class="form-control" id="amountpaid" name="amountpaid" type="text" autocomplete="off" onclick="" readonly />
    </div>
  </div>
  <div class="form-group balance">
    <label class="control-label " id="balance" for="balance">Balance Given</label>
    <?php
      /**
       * NOTE: The Variable used througout this region has been decalared at the top of this file
       * as it is used by different sections of the HTML.
       */
      foreach ($mapQntToMultipliers as $key => $val) {
        $cbid = $mapCheckboxToLabel[$key];
        $amnt = $mapCheckboxToAmnt[$key];
        if ($key!=="1kqty") {
    ?>
    <div class="custom-control custom-checkbox mb-3 form-inline">
      <input type="checkbox" class="custom-control-input"
        id="<?php echo $cbid; ?>_bal" name="<?php echo $cbid; ?>_bal"
          onchange="doMath.focusAmountInput(this)" />
      <label class="custom-control-label amnt" for="<?php echo $cbid; ?>_bal">N<?php echo $amnt; ?></label>
      <label class="mr-sm-3" for="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input class="form-control amnt-input" id="<?php echo $key; ?>_bal" name="<?php echo $key; ?>_bal"
        type="number" value="" onfocus="doMath.multplyDenByNumb(this, true)" disabled />
      <input class="form-control" id="<?php echo $val; ?>_bal" name="<?php echo $val; ?>_bal"
        type="text" value="" readonly />
    </div>
    <hr class="style13">
        <?php }
    } ?>
    <div class="form-group">
      <label class="control-label " for="total-balance">Total Balance to be given</label>
      <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-money"></i></div>
        <input class="form-control" id="total-balance" name="total-balance" type="text" autocomplete="off" onclick="" readonly />
      </div>
    </div>
  </div>
  <div id="info-summary">
    <h3>Transaction Information</h3>
    <div id="transaction-info">
      <div class="form-group">
        <label class="control-label " for="amount-paid">Amount Paid</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-money"></i></div>
          <input class="form-control" id="amount-paid" name="amount-paid" type="text" autocomplete="off" onclick="" readonly />
        </div>
      </div>
      <div class="form-group">
        <label class="control-label " for="actual-tithe">Actual Tithe</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-money"></i></div>
          <input class="form-control" id="actual-tithe" name="actual-tithe" type="text" autocomplete="off" onclick="" readonly />
        </div>
      </div>
      <div class="form-group">
        <label class="control-label " for="balance-given">Balance Given</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-money"></i></div>
          <input class="form-control" id="balance-given" name="balance-given" type="text" autocomplete="off" onclick="" readonly />
        </div>
      </div>
    </div>
  </div>
  <div id="summary-section">
    <h3>Denominations Summary</h3>
    <div id="den-summary">
      <div class="summary info">
          <div class="table-type heading">Denominations</div>
          <div class="table-type body">1000 NGN</div><div class="table-type body">500 NGN</div>
          <div class="table-type body">200 NGN</div><div class="table-type body">100 NGN</div>
          <div class="table-type body">50 NGN</div><div class="table-type body">20 NGN</div>
          <div class="table-type body">10 NGN</div><div class="table-type body">5 NGN</div>
      </div>
      <div class="summary paid">
          <div class="table-type heading">Collected</div>
          <?php foreach($qnt as $key=>$val) { ?>
            <div class="table-type body" id="<?php echo $key ?>_summary">0</div>
          <?php } ?>
      </div>
      <div class="summary bal">
          <div class="table-type heading">Balance</div>
          <?php foreach($qnt as $key=>$val) { ?>
            <div class="table-type body" id="<?php echo $val ?>_summary">0</div>
          <?php } ?>
      </div>
    </div>
  </div>
  <div class="btn-holder">
    <button type="submit" class="btn btn-success btn-lg"><i class='fa fa-money'>&nbsp;</i>Post Transaction</button>
  </div>
</fieldset>