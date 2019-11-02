<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

//Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

//Get current page.
$page = filter_input(INPUT_GET, 'page');

//Per page limit for pagination.
$pagelimit = 50;

if (!$page) {
    $page = 1;
}

// If filter types are not selected we show latest created data first
if (!$filter_col) {
    $filter_col = "date_received";
}
if (!$order_by) {
    $order_by = "Desc";
}

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('invoicenum', 'memid', 'Name_member', 'sex', 'band_name','Amount_Paid','payment_mode');

//Start building query according to input parameters.
// If search string
if ($search_string) 
{
    $db->where('Name_member', '%' . $search_string . '%', 'like');
    $db->orwhere('invoicenum', '%' . $search_string . '%', 'like');
    $db->orwhere('memid', '%' . $search_string . '%', 'like');
}

//If order by option selected
if ($order_by)
{
    $db->orderBy($filter_col, $order_by);
}

//Set pagination limit
$db->pageLimit = $pagelimit;

//Get result of the query.
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
            <h1 class="page-header">Daily Transactions View</h1>
        </div>
        
    </div>
        <?php include('./includes/flash_messages.php') ?>
    <!--    Begin filter section-->
    <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Search</label>
            <input type="text" class="form-control" id="input_search" name="search_string" value="<?php echo $search_string; ?>">
            <label for ="input_order">Order By</label>
            <select name="filter_col" class="form-control">

                <?php
                foreach ($filter_options as $option) {
                    ($filter_col === $option) ? $selected = "selected" : $selected = "";
                    echo ' <option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                }
                ?>

            </select>

            <select name="order_by" class="form-control" id="input_order">

                <option value="Asc" <?php
                if ($order_by == 'Asc') {
                    echo "selected";
                }
                ?> >Asc</option>
                <option value="Desc" <?php
                if ($order_by == 'Desc') {
                    echo "selected";
                }
                ?>>Desc</option>
            </select>
            <input type="submit" value="Go" class="btn btn-primary">

        </form>
    </div>
<!--   Filter section end-->

    <hr>

   
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
                    <td><?php echo htmlspecialchars($row['payment_mode']) ?> </td>
	                <td>
					
				</tr>

						<!-- Delete Confirmation Modal-->
					 
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
                echo '<li' . $li_class . '><a href="daily_transact_grid.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    <!--    Pagination links end-->

</div>
<!--Main container end-->


<?php include_once './includes/footer.php'; ?>

