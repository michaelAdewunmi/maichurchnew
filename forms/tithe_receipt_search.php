
<div class="well text-center filter-form">
    <form class="form form-inline" action="">
    <div class="form-group">
    <label for="receipt_id">Surname/Transaction/Receipt ID:</label>
    <input type="text" class="form-control" id="receipt_id" name="receipt_id">
    </div>
   


 <button type="submit" class="btn btn-primary mb-2"><i class='fa fa-search'>&nbsp;</i>Search Transaction</button>
 </form>
 </div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $search_string = filter_input(INPUT_POST, 'receipt_id');
?>
<div id="tithe_trans">
<table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="header">TranID</th>
                <th>ReceiptID</th>
                <th>Member Name</th>
                <th>Description</th>
                <th>Transaction Date</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($search_string) 
            {
                $db = getDbInstance();
                //$select = array('invoicenum','trans_id', 'Name_member', 'Amount_Paid', 'payment_description');
                //$db->where('reversal_status' , '0');
                $db->where('Name_member', '%' . $search_string . '%', 'like');
                $db->orwhere('invoicenum', '%' . $search_string . '%', 'like');
                $db->orwhere('trans_id', '%' . $search_string . '%', 'like');
               
              
               
                $row = $db->get('tb_payment');
               // $tithe_trans = $db->get("tb_payment");
            if ($db->count >=1) {
                foreach($row as $value){
                ?>
                <tr>
	                <td><?php echo htmlspecialchars($value['trans_id']) ?></td>
                    <td><?php 
                    if(htmlspecialchars($value['reversal_status'])==1) {
                        echo htmlspecialchars($value['invoicenum'])."(RVSL)" ;
                    }else
                    {
                        echo htmlspecialchars($value['invoicenum']);
                    }
                    ?></td>
	                <td><?php echo htmlspecialchars($value['Name_member']) ?></td>
                    <td><?php echo htmlspecialchars($value['payment_description']) ?> </td>
                    <td><?php echo htmlspecialchars($value['date_received']) ?> </td>
	                <td><?php echo htmlspecialchars($value['Amount_Paid']) ?> </td>
	                <td>
					<a href="" class="btn btn-primary" style="margin-right: 8px;" data-toggle="modal" data-target="#confirm-reverse-<?php echo $value['invoicenum'] ?>"><span class="glyphicon glyphicon-repeat"></span>
                    <a href="tithe_receipt.php?intv=<?php echo base64_encode($value['invoicenum']) ?>&route=search" class="btn btn-primary" style="margin-right: 8px;"><span class="glyphicon glyphicon-print"></span>
					
				</tr>
                    <?php  } }
                } 
                } ?>
						<!-- Reversal Confirmation Modal-->
					 <div class="modal fade" id="confirm-reverse-<?php echo $value['invoicenum'] ?>" role="dialog">
					    <div class="modal-dialog">
					      <form action="tithe_trans_reversal.php" method="POST">
					      <!-- Modal content-->
						      <div class="modal-content">
						        <div class="modal-header">
						          <button type="button" class="close" data-dismiss="modal">&times;</button>
						          <h4 class="modal-title">Confirm</h4>
						        </div>
						        <div class="modal-body">
						      
						        		<input type="hidden" name="reverse_id" id = "reverse_id" value="<?php echo $value['invoicenum'] ?>">
						        	
						          <p>Are you sure you want to reverse this transaction?</p>
						        </div>
						        <div class="modal-footer">
						        	<button type="submit" class="btn btn-default pull-left">Yes</button>
						         	<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						        </div>
						      </div>
					      </form>
					      
					    </div>
  					</div>
          
        </tbody>
    </table>


</div>