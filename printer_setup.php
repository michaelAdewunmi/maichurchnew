<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

//Get Input data from query string
//Get current page.
$page = filter_input(INPUT_GET, 'page');

//Per page limit for pagination.
$pagelimit = 20;

if (!$page) {
    $page = 1;
}


//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();

//Start building query according to input parameters.
// If search string
$db->where("admin_type", "cashier");
$select = "user_name, surname, firstname";
$cashier = $db->get('admin_accounts',null, $select);

//Set pagination limit
$db->pageLimit = $pagelimit;

//Get result of the query.
$members = $db->paginate("printer_assign", $page);
$total_pages = $db->totalPages;

// get columns for order filter

include_once 'includes/header.php';
?>

<!--Main container start-->
<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-6">
            <h1 class="page-header">Assign Printers</h1>
        </div>
        
    </div>
        <?php include('./includes/flash_messages.php') ?>
    <!--    Begin filter section-->
    <div class="well text-center filter-form">
        <form class="form form-inline" method="post" action="setup_printer_insert.php" id="receipt_assign" enctype="multipart/form-data">
        <div class="form-group">
        <label for="receiptnumb">Printer Name *</label>
          <input type="text" name="printer_name" id="printer_name" placeholder="Printer Name" class="form-control" required="required">
        </div> 
        <div class="form-group">
        <label for="receiptnumb">Printer Model *</label>
          <input type="text" name="printer_model" id="printer_model" placeholder="Printer Model" class="form-control" required="required">
        </div> 
    <div class="form-group">
        <label>Cashier *</label>
           
            <select name="cashier" class="form-control selectpicker" required>
                <option value=" ">Please select Cashier</option>
                <?php
                
                foreach ($cashier as $cashmanagers) {
                    echo ' <option value="' . $cashmanagers['user_name'] . '">' . $cashmanagers['user_name'] . '</option>'; 
                     
                }
           
                ?>
            </select>
    </div>  
            <input type="submit" value="Assign" class="btn btn-primary">

        </form>
    </div>
<!--   Filter section end-->

    <hr>

   
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="header">#</th>
                <th>Printer Name</th>
                <th>Printer Model</th>
                <th>Assigned To</th>
                <th>Assigned Date</th>
                <th>Assigned By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
                
                foreach ($members as $row) :
                
                ?>
                <tr>
	                <td><?php  ?></td>
	                <td><?php echo htmlspecialchars($row['PrinterName']); ?></td>
	                <td><?php echo htmlspecialchars($row['PrinterModel']) ?></td>
	                <td><?php echo htmlspecialchars($row['AssignedTo']) ?> </td>
                    <td><?php echo htmlspecialchars($row['AssignedBy']); ?></td>
                    <td><?php echo htmlspecialchars($row['AssignedDate']) ?></td>
	                                  
	                <td>
					
				</tr>

						
					      </form>
					      
					    </div>
  					</div>
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
                echo '<li' . $li_class . '><a href="assign_receipt_book.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></div>';
        }
        ?>
    </div>
    <!--    Pagination links end-->

</div>
<!--Main container end-->


<?php include_once './includes/footer.php'; ?>
<script type="text/javascript">
$(document).ready(function(){
   $("#receipt_assign").validate({
       rules: {
        receiptnumb: {
                required: true,
                minlength: 7
            },
            leafletnumb: {
                required: true,
                minlength: 3
            },
            cashier: {
                required: true
            },
            
        }
    });
});
</script>
