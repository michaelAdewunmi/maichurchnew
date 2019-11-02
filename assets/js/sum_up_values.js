function sum_up_values_old()
{

var total_amount_sumed;

if((document.getElementById('5ttl').value)=="")
{
    var ttl5 = 0;
}
else
{
    var ttl5 = document.getElementById('5ttl').value;
}

if((document.getElementById('10ttl').value)=="")
{
    var ttl10 =0;
}
else
{
    var ttl10 = document.getElementById('10ttl').value;
}

if((document.getElementById('20ttl').value)=="")
{
    var ttl20 =0;
}
else
{
    var ttl20 = document.getElementById('20ttl').value;
}


if((document.getElementById('50ttl').value)=="")
{
    var ttl50 = 0;
}
else
{
    var ttl50 = document.getElementById('50ttl').value;
}

if((document.getElementById('1httl').value)=="")
{
    var ttl100 =0;
}
else
{
    var ttl100 = document.getElementById('1httl').value;
}

if((document.getElementById('2httl').value)=="")
{
    var ttl200 =0;
}
else
{
    var ttl200 = document.getElementById('2httl').value;
}
if((document.getElementById('5httl').value)=="")
{
    var ttl500 =0;
}
else
{
    var ttl500 = document.getElementById('5httl').value;
}

if((document.getElementById('1kttl').value)=="")
{
    var ttl1000 =0;
}
else
{
    var ttl1000 = document.getElementById('1kttl').value;
}

total_amount_sumed = parseInt(ttl1000) + parseInt(ttl500) + parseInt(ttl200) + parseInt(ttl100) + parseInt(ttl50) + parseInt(ttl20) + parseInt(ttl10) + parseInt(ttl5)

document.getElementById('amountpaid').value = total_amount_sumed;
//document.getElementById('amountpaid').value = total_amount_sumed.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}


