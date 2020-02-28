<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';
require_once 'includes/header.php';

$db = getDbInstance();
$db->where("day", date('Y-m-d'));
$db->where("day_ended", true);
$db->where("day_ended_for", $_SESSION["username"]);
$row = $db->get('start_and_end_day_controller');
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12"><h2 class="page-header">Cash Analysis</h2></div>
        <?php
        if ($db->count<1) {
            echo '<h2 class="small_header">'.
                "Sorry! The Day hasn't ended yet! Cash Analysis is only possible at the end of the day".
                "</h2>";
        } else { ?>
            <div id="cash_analysis_wrapper">
                <h2 class="cash_analysis_header">Analysis for tithes collected on <?php echo substr(date_format(date_create(date("Y-m-d")), "r"), 0, 16); ?></h2>
                <?php
                $db = getDbInstance();
                $db->where("date_received_no_time", date('Y-m-d'));
                $row = $db->get('vw_tithe_payment_with_balance');

                if ($db->count<1) {
                    echo '<h2 class="small_header">'.
                        "Oops! It seems There is no tithe posted for this day.".
                        "</h2>";
                } else {
                    $den_starting_the_day = array (
                        'Dem1000' => '0', 'Dem500' => '0', 'Dem200' => '0',
                        'Dem100' => '0', 'Dem50' => '0', 'Dem20' => '0',
                        'Dem10' => '0', 'Dem5' => '0'
                    );
                    $den_collected = array ();
                    $den_given = array();
                    $den_left = array();
                    $total_amount_collected = 0;
                    $total_opening_amount = 0;
                    $total_amount_given = 0;
                    $total_amount_left = 0;

                    $den_coll_and_given = array (
                        'Dem1000' => 'balDenom1000', 'Dem500' => 'balDenom500', 'Dem200' => 'balDenom200',
                        'Dem100' => 'balDenom100', 'Dem50' => 'balDenom50', 'Dem20' => 'balDenom20',
                        'Dem10' => 'balDenom10', 'Dem5' => 'balDenom5'
                    );
                    $count_all_tithes = 1;
                    foreach ($row as $tithe_info) {
                        foreach ($den_coll_and_given as $collected => $given) {
                            $den_collected[$collected] = !isset($den_collected[$collected]) ?
                                intval($tithe_info[$collected]) : intval($den_collected[$collected])+intval($tithe_info[$collected]);
                            $den_given[$collected] = !isset($den_given[$collected]) ?
                            intval($tithe_info[$given]) : intval($den_given[$collected])+intval($tithe_info[$given]);
                            if ($count_all_tithes===count($row)) {
                                //echo $den_given[$collected];
                                $den_left[$collected] = intval(
                                    ($den_starting_the_day[$collected] + $den_collected[$collected]) - $den_given[$collected]
                                );
                            }
                        }
                        $count_all_tithes++;

                    }
                    ?>
                    <div id="summary-section">
                        <h3>Tithes Denominations Summary</h3>
                        <div id="den-summary" class="is_analysis_page">
                            <div class="summary info">
                                <div class="table-type heading">All Denominations</div>
                                <div class="table-type body">1000 NGN</div><div class="table-type body">500 NGN</div>
                                <div class="table-type body">200 NGN</div><div class="table-type body">100 NGN</div>
                                <div class="table-type body">50 NGN</div><div class="table-type body">20 NGN</div>
                                <div class="table-type body">10 NGN</div><div class="table-type body">5 NGN</div>
                            </div>
                            <div class="summary paid">
                                <div class="table-type heading">Denoms. Collected</div>
                                <?php foreach ($den_collected as $key => $val) { ?>
                                    <div class="table-type body" id="<?php echo $key ?>_collected"><?php echo $val; ?></div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Denoms. Given</div>
                                <?php foreach ($den_given as $dkey => $dval) { ?>
                                    <div class="table-type body" id="<?php echo $dkey ?>_given"><?php echo $dval; ?></div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Denoms. Left</div>
                                <?php foreach ($den_left as $lkey => $lval) { ?>
                                    <div class="table-type body" id="<?php echo $lkey ?>_left">
                                        <?php echo intval($den_left[$lkey]); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Opening Amount (NGN)</div>
                                <?php foreach ($den_starting_the_day as $stkey => $stval) {
                                    $amount_opened_by_den = intval($den_starting_the_day[$stkey] * intval(substr($stkey, 3)));
                                    $total_opening_amount = $total_opening_amount + $amount_opened_by_den;
                                        ?>
                                    <div class="table-type body" id="<?php echo $stkey ?>_amnt_strtd">
                                        <?php echo number_format(intval($den_starting_the_day[$stkey] * intval(substr($stkey, 3)))); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Amount Collected (NGN)</div>
                                <?php foreach ($den_collected as $ckey => $cval) {
                                    $amount_collected_by_den = intval($den_collected[$ckey] * intval(substr($ckey, 3)));
                                    $total_amount_collected = $total_amount_collected + $amount_collected_by_den;
                                    ?>
                                    <div class="table-type body" id="<?php echo $ckey ?>_amnt_coll">
                                        <?php echo number_format($amount_collected_by_den); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Amount Given (NGN)</div>
                                <?php foreach ($den_given as $gkey => $gval) {
                                    $amount_given_by_den = intval($den_given[$gkey] * intval(substr($gkey, 3)));
                                    $total_amount_given = $total_amount_given + $amount_given_by_den;
                                    ?>
                                    <div class="table-type body" id="<?php echo $gkey ?>_amnt_given">
                                        <?php echo number_format($amount_given_by_den); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="summary bal">
                                <div class="table-type heading">Amount Left (NGN)</div>
                                <?php foreach ($den_left as $leftkey => $leftval) {
                                    $amount_left_by_den = intval($den_left[$leftkey] * intval(substr($leftkey, 3)));
                                    $total_amount_left = $total_amount_left + $amount_left_by_den;
                                    ?>
                                    <div class="table-type body" id="<?php echo $leftkey ?>_amnt_given">
                                        <?php echo number_format(intval($den_left[$leftkey] * intval(substr($leftkey, 3)))); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div id="final_summary">
                            <p><span class="cash_final_summary">Total Opening Amount:</span> NGN <?php echo number_format($total_opening_amount); ?></p>
                            <p><span class="cash_final_summary">Total Amount Received:</span> NGN <?php echo number_format($total_amount_collected); ?></p>
                            <p><span class="cash_final_summary">Total Amount Disbursed:</span> NGN <?php echo number_format($total_amount_given); ?></p>
                            <p><span class="cash_final_summary">Total Amount Left:</span> NGN <?php echo number_format($total_amount_left); ?></p>
                        </div>
                    </div>
                    <?php
                } // End if for No Tithe Posting for the day
                ?>
                <script src="assets/js/jquery1.11.1.min.js"></script>
                <script src="assets/js/jquery.min.js"></script>
                <script src="assets/js/moment.min.js"></script>

                <script src="assets/js/typeahead.min.js"></script>
                <script src="assets/js/modepick.js"></script>
                <script src="assets/js/currencyvalue.js"></script>
                <script src="assets/js/sum_up_values.js"></script>
            </div>
            <?php
        } // End "end of day" if statement
        ?>
    </div>
</div>
<?php
require_once 'includes/footer.php'; ?>