class DenominationsTransformation {
    constructor() {
        this.mapQntToMultipliers = {
            '1kqty': 1000, '5hqty': 500, '2hqty': 200, '1hqty': 100, '50qty': 50, '20qty': 20, '10qty': 10, '5qty': 5
        }

        this.mapQntBalToMultipliers = {
            '5hqty_bal': 500, '2hqty_bal': 200, '1hqty_bal': 100, '50qty_bal': 50, '20qty_bal': 20, '10qty_bal': 10, '5qty_bal': 5
        }

        this.quantities = [
            '5ttl', '10ttl', '20ttl', '50ttl', '1httl', '2httl',
            '5httl', '1kttl'
        ]

        this.quantities_bal = [
            '5ttl_bal', '10ttl_bal', '20ttl_bal', '50ttl_bal', '1httl_bal', '2httl_bal',
            '5httl_bal'
        ]
    }

    multplyDenByNumb(element, isBal) {
        window.addEventListener('keyup', (e) => {
            this.cnvrtToAmnt(element, isBal);
        })
    }

    cnvrtToAmnt(e, isBal=false) {
        const parentId = e.getAttribute('id');
        const sibling = e.nextElementSibling;
        const val = e.value==='' ? 0 : Number(e.value);
        let curr_value;
        if(!isBal) {
            if (val!==NaN && typeof(val)==="number"){
                curr_value = val * this.mapQntToMultipliers[parentId];
                sibling.value = curr_value;
            } else {
                alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
            }
        } else {
            if (val!==NaN && typeof(val)==="number"){
                curr_value = val * this.mapQntBalToMultipliers[parentId];
                sibling.value = curr_value;
            } else {
                alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
            }
        }
        this.sum_up_values(isBal);
        this.PasteDenInSummary(parentId, isBal);
    }

    PasteDenInSummary(id, isBal=false) {
        const val=document.getElementById(`${id}`).value;
        //if(!isBal) {
        if(val!=='' && val!==NaN) {
            document.getElementById(`${id}_summary`).innerHTML = document.getElementById(`${id}`).value;
        } else {
            document.getElementById(`${id}_summary`).innerHTML = 0;
        }
    }

    sum_up_values(isBal=false) {
        let total_amount=0;

        if(!isBal) {
            this.quantities.forEach(qnt=>{
                let val = document.getElementById(qnt).value;
                val = val==='' ? 0 : Number(val);
                if( val != NaN && typeof(val)==="number") {
                    total_amount+=val;
                }
            })
            document.getElementById('amountpaid').value = total_amount;
        } else {
            this.quantities_bal.forEach(qnt=>{
                let val = document.getElementById(qnt).value;
                console.log(val);
                val = val==='' ? 0 : Number(val);
                if( val != NaN && typeof(val)==="number") {
                    total_amount+=val;
                } else {
                    alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
                }
            })
            document.getElementById('total-balance').value = total_amount;
        }
        this.getTransactionSummary();
        //showDenominationsSummary();
    }

    getTransactionSummary() {
        const totalAmount = document.getElementById('amountpaid').value;
        const balanceGiven = document.getElementById('total-balance').value;
        const actualTithe = totalAmount - balanceGiven;

        document.getElementById('actual-tithe').value = actualTithe;
        document.getElementById('amount-paid').value = totalAmount;
        document.getElementById('balance-given').value = balanceGiven;
    }

    focusAmountInput(e, parentId=null) {
        const  totalDiv = e.parentNode.lastElementChild;
        const quantityDiv = e.parentNode.lastElementChild.previousElementSibling;
        const quantityDivId = quantityDiv.getAttribute("id");
        if(e.checked===true) {
            quantityDiv.disabled = false;
            totalDiv.readonly = false;
            this.sum_up_values();
            this.sum_up_values(true);
            this.PasteDenInSummary(quantityDivId);
        } else {
            quantityDiv.value="";
            quantityDiv.disabled = true;
            totalDiv.value=""
            totalDiv.disabled = true;
            this.sum_up_values();
            this.sum_up_values(true);
            this.PasteDenInSummary(quantityDivId);
        }
    }
}

const doMath = new DenominationsTransformation;

/*

const

const

// function showDenominationsSummary() {
//     mapQntBalToMultipliers.forEach(quant => {

//     })
// }

function

function cnvrtToAmnt(e, is_bal=false) {
    const parentId = e.getAttribute('id');
    const sibling = e.nextElementSibling;
    const val = e.value==='' ? 0 : Number(e.value);
    let curr_value;
    if(!is_bal) {
        if (val!==NaN && typeof(val)==="number"){
            curr_value = val * mapQntToMultipliers[parentId];
            sibling.value = curr_value;
        } else {
            alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
        }
    } else {
        if (val!==NaN && typeof(val)==="number"){
            curr_value = val * mapQntBalToMultipliers[parentId];
            sibling.value = curr_value;
        } else {
            alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
        }
    }
}


function sum_up_values(isBal=false) {
    let total_amount=0;
    const quantities = [
        '5ttl', '10ttl', '20ttl', '50ttl', '1httl', '2httl',
        '5httl', '1kttl'
    ]

    const quantities_bal = [
        '5ttl_bal', '10ttl_bal', '20ttl_bal', '50ttl_bal', '1httl_bal', '2httl_bal',
        '5httl_bal'
    ]

    if(!isBal) {
        quantities.forEach(qnt=>{
            let val = document.getElementById(qnt).value;
            val = val==='' ? 0 : Number(val);
            if( val != NaN && typeof(val)==="number") {
                total_amount+=val;
            }
        })
        document.getElementById('amountpaid').value = total_amount;
    } else {
        quantities_bal.forEach(qnt=>{
            let val = document.getElementById(qnt).value;
            console.log(val);
            val = val==='' ? 0 : Number(val);
            if( val != NaN && typeof(val)==="number") {
                total_amount+=val;
            } else {
                alert("Oops! There is an input Error. Please Ensure You Type Only Numbers (No need for commas).");
            }
        })
        document.getElementById('total-balance').value = total_amount;
    }
    getTransactionSummary();
    //showDenominationsSummary();
}

function getTransactionSummary() {
    const totalAmount = document.getElementById('amountpaid').value;
    const balanceGiven = document.getElementById('total-balance').value;
    const actualTithe = totalAmount - balanceGiven;

    console.log(totalAmount)
    console.log(balanceGiven)
    console.log(totalAmount)

    document.getElementById('actual-tithe').value = actualTithe;
    document.getElementById('amount-paid').value = totalAmount;
    document.getElementById('balance-given').value = balanceGiven;
}

function focusAmountInput(e) {
    const  totalDiv = e.parentNode.lastElementChild;
    const quantityDiv = e.parentNode.lastElementChild.previousElementSibling;
    if(e.checked===true) {
        quantityDiv.disabled = false;
        totalDiv.readonly = false;
    } else {
        quantityDiv.value="";
        quantityDiv.disabled = true;
        totalDiv.value=""
        totalDiv.disabled = true;
    }
}

/*
function k1convert()
{
	if(document.getElementById('1kqty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('1kqty').value));
            var curr_value = d_qty * 1000;
            document.getElementById('1kttl').value =curr_value;
        }
}

function h5convert()
{
	if(document.getElementById('5hqty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('5hqty').value));
            var curr_value = d_qty * 500;
            document.getElementById('5httl').value =curr_value;
        }
}

function h2convert()
{
	if(document.getElementById('2hqty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('2hqty').value));
            var curr_value = d_qty * 200;
            document.getElementById('2httl').value =curr_value;

        }
}

function h1convert()
{
	if(document.getElementById('1hqty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('1hqty').value));
            var curr_value = d_qty * 100;
            document.getElementById('1httl').value =curr_value;

        }
}

function n50convert()
{
	if(document.getElementById('50qty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('50qty').value));
            var curr_value = d_qty * 50;
            document.getElementById('50ttl').value =curr_value;

        }
}

function n20convert()
{
	if(document.getElementById('20qty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('20qty').value));
            var curr_value = d_qty * 20;
            document.getElementById('20ttl').value =curr_value;
        }
}

function n10convert()
{
	if(document.getElementById('10qty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('10qty').value));
            var curr_value = d_qty * 10;
            document.getElementById('10ttl').value =curr_value;
        }
}

function n5convert()
{
	if(document.getElementById('5qty').value!="")
		{
            var floor = Math.floor;
            var d_qty = floor(parseFloat(document.getElementById('5qty').value));
            var curr_value = d_qty * 5;
            document.getElementById('5ttl').value =curr_value;
        }
}
*/

