<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

//Get Input data from query string

//Get current page.
$page = filter_input(INPUT_GET, 'page');

//Per page limit for pagination.
$pagelimit = 25;

if (!$page) {
    $page = 1;
}

if(isset($_SESSION['reverse'])) {
    echo '<pre>';
    var_dump($_SESSION['reverse']);
    echo '</pre>';

}

if(isset($_SESSION['reverse2'])) {
    echo '<pre>';
    var_dump($_SESSION['reverse2']);
    echo '</pre>';

}
// If filter types are not selected we show latest created data first

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('invoicenum', 'memid', 'Name_member', 'sex', 'band_name','Amount_Paid','payment_mode','date_received');
$db->where('reversal_status', 1);
$db->orderBy('invoicenum','DESC');

//Start building query according to input parameters.
// If search string


//If order by option selected


//Set pagination limit
$db->pageLimit = $pagelimit;

//Get result of the query.
//$db->where('date_received', '%' . $daily_now . '%', 'like');
$members = $db->arraybuilder()->paginate("tb_payment", $page, $select);
$total_pages = $db->totalPages;

// get columns for order filter
foreach ($members as $value) {
    foreach ($value as $col_name => $col_value) {
        $filter_options[$col_name] = $col_name;
    }
    //execute only once
    break;
}
include_once 'includes/header.php';
?>

<!--Main container start-->
<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-6">
            <h1 class="page-header">Reversed Transactions View</h1>
        </div>

    </div>
        <?php include('./includes/flash_messages.php') ?>
    <!--    Begin filter section-->





    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="header">#</th>
                <th>Receipt Number</th>
                <th>Payee ID</th>
                <th>Payee Name</th>
                <th>Sex</th>
                <th>Payee Band</th>
                <th>Amount Paid</th>
                <th>Transact Date</th>
                <th>Payment Mode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php


                foreach ($members as $row) :

                ?>
                <tr>
	                <td><?php  ?></td>
	                <td><?php echo htmlspecialchars($row['invoicenum']); ?></td>
	                <td><?php echo htmlspecialchars($row['memid']) ?></td>
	                <td><?php echo htmlspecialchars($row['Name_member']) ?> </td>
                    <td><?php echo htmlspecialchars($row['sex']); ?></td>
	                <td><?php echo htmlspecialchars($row['band_name']) ?></td>
	                <td><?php echo htmlspecialchars($row['Amount_Paid']) ?> </td>
                    <td><?php echo htmlspecialchars($row['date_received']) ?> </td>
                    <td><?php echo htmlspecialchars($row['payment_mode']) ?> </td>
	                <td>
					<a href="view_reversal_transact.php?page=null&trans_id=<?php echo base64_encode(htmlspecialchars($row['invoicenum']));?>"  class="btn btn-primary" style="margin-right: 8px;"><span class="glyphicon glyphicon-folder-open"></span></td>
				</tr>

            <?php


        endforeach; ?>
        </tbody>
    </table>



<!--    Pagination links-->
    <div class="text-center">

        <?php
        if (!empty($_GET)) {
            //we must unset $_GET[page] if previously built by http_build_query function
            unset($_GET['page']);
            //to keep the query sting parameters intact while navigating to next/prev page,
            $http_query = "?" . http_build_query($_GET);
        } else {
            $http_query = "?";
        }
        //Show pagination links
        if ($total_pages > 1) {
            echo '<ul class="pagination text-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                ($page == $i) ? $li_class = ' class="active"' : $li_class = "";
                echo '<li' . $li_class . '><a href="reversal_transact_grid.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    <!--    Pagination links end-->

</div>
<!--Main container end-->


<?php include_once './includes/footer.php'; ?>

