function sum_up_values() {
    var total_amount;

    const quantities = [
        '5ttl', '10ttl', '20ttl', '50ttl', '1httl', '2httl',
        '5httl', '1kttl'
    ]

    quantities.forEach(qnt=>{
        let val = document.getElementById(qnt).value
        if(val !== '' && Number(val) != NaN && typeof(Number(val)==="number")) {
            total_amount+=Number(val);
        }
    })
    document.getElementById('amountpaid').value = total_amount;
}