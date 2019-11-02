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
            <h2 class="page-header">Reverse Tithe</h2>
        </div>
        
</div>
    <form class="form" action="tithe_reversal_commit.php" method="post"  id="select_reversal_option_form" enctype="multipart/form-data">
       <?php  include_once('select_reversal_option.php'); ?>
    </form>
</div>






<style>
input.datepicker-here {width: 100%;}
    </style>

<script type="text/javascript">

$(document).ready(function(){
   $("#select_reversal_option_form").validate({
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
<script type="text/javascript">
document.getElementById('cashmode').style.display="none";
document.getElementById('cardmode').style.display="none";
document.getElementById('directmode').style.display="none";
document.getElementById('chequemode').style.display="none";
document.getElementById('typeahead').disabled=true;
document.getElementById('duration').disabled=true;
document.getElementById('paymode').disabled=true;
document.getElementById('reversalbtn').disabled=true;


</script>
<script>
    $(document).ready(function(){
    $('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'search.php?key=%QUERY',
        limit : 50
    });
});
    </script>
<?php include_once ('includes/footer.php'); ?>
