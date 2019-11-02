function select_mode()
{
	if(document.getElementById('paymode').value=="Cash")
		{
                 
            document.getElementById('amountpaid').readOnly = true;
            document.getElementById('amountpaid').value = "";
            document.getElementById('cashmode').style.display="block";
            document.getElementById('cardmode').style.display="none";
            document.getElementById('chequemode').style.display="none";
            document.getElementById('directmode').style.display="none";
            }
    else if(document.getElementById('paymode').value=="Cheque")
		{
                  
            document.getElementById('amountpaid').readOnly = false;
            document.getElementById('amountpaid').value = "";
            document.getElementById('cashmode').style.display="none";
            document.getElementById('cardmode').style.display="none";
            document.getElementById('chequemode').style.display="block";
            document.getElementById('directmode').style.display="none";
            }
      else if(document.getElementById('paymode').value=="Direct Lodgement")
		{
                  
            document.getElementById('amountpaid').readOnly = false;
            document.getElementById('amountpaid').value = "";
            document.getElementById('cashmode').style.display="none";
            document.getElementById('cardmode').style.display="none";
            document.getElementById('chequemode').style.display="none";
            document.getElementById('directmode').style.display="block";
            }
      else if(document.getElementById('paymode').value=="Card")
		{
                  
            document.getElementById('amountpaid').readOnly = false;
            document.getElementById('amountpaid').value = "";
            document.getElementById('cashmode').style.display="none";
            document.getElementById('cardmode').style.display="block";
            document.getElementById('chequemode').style.display="none";
            document.getElementById('directmode').style.display="none";
            
            }
      else if(document.getElementById('paymode').value=="")
		{
             alert("Please select a payment mode!");     
            document.getElementById('amountpaid').readOnly = true;
            document.getElementById('amountpaid').value = "";
            document.getElementById('cashmode').style.display="none";
            document.getElementById('cardmode').style.display="none";
            document.getElementById('chequemode').style.display="none";
            document.getElementById('directmode').style.display="none";
            }
      
	
}
function reversal_option()
{
      if(document.getElementById('reversal_options').value=="Wrong Amount")
      {
            document.getElementById('reversal_opts').value = document.getElementById('reversal_options').value
            document.getElementById('reversal_options').disabled=true;
            document.getElementById('amountpaid').readOnly=true;  
            document.getElementById('typeahead').disabled=true;
            document.getElementById('typeahead').value='';
            document.getElementById('duration').disabled=true;
            document.getElementById('paymode').disabled=false;
            document.getElementById('reversalbtn').disabled=false;
            document.getElementById('duration').value = document.getElementById('duration_hid').value
            
            }
      else if(document.getElementById('reversal_options').value=="Wrong Payee Details")
      {
            document.getElementById('reversal_opts').value = document.getElementById('reversal_options').value
            document.getElementById('reversal_options').disabled=true;
            document.getElementById('amountpaid').readOnly=true;      
            document.getElementById('typeahead').disabled=false;
            document.getElementById('duration').disabled=true;
            document.getElementById('paymode').value='';
            document.getElementById('paymode').disabled=true;
            document.getElementById('cashmode').style.display="none";
            document.getElementById('cardmode').style.display="none";
            document.getElementById('reversalbtn').disabled=false;
            document.getElementById('duration').value = document.getElementById('duration_hid').value
            }
           else if(document.getElementById('reversal_options').value=="Wrong Period Selection")
            {
                  document.getElementById('reversal_opts').value = document.getElementById('reversal_options').value
                  document.getElementById('reversal_options').disabled=true;
                  document.getElementById('amountpaid').readOnly=true;      
                  document.getElementById('typeahead').disabled=true;
                  document.getElementById('duration').disabled=false;
                  document.getElementById('reversalbtn').disabled=false;
                  document.getElementById('paymode').disabled=true;
                  document.getElementById('duration').value = '';
                  }
}