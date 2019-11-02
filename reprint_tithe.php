<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';



//serve POST method, After successful insert, redirect to members.php page.

require_once 'includes/header.php'; 
?>

<div id="page-wrapper">
<div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Search Tithe Transactions</h2>
        </div>
        
</div>
    <form class="form" action="" method="post"  id="tithe_reserval_form" enctype="multipart/form-data">
       <?php  include_once('./forms/tithe_receipt_search.php'); ?>
    </form>
</div>


<script src="assets/js/jquery1.11.1.min.js"></script>
<script src="assets/js/jquery.min.js"></script>



<style>
input.datepicker-here {width: 100%;}
    </style>


<script type="text/javascript">

$(document).ready(function(){
   $("#tithe_reserval_form").validate({
       rules: {
            receipt_id: {
                required: true,
                minlength: 3
            },
            
        }
    });
});
</script>
<script type="text/javascript">
//document.getElementById('tithe_trans').style.display="none";
</script>

<?php include_once ('includes/footer.php'); ?>
