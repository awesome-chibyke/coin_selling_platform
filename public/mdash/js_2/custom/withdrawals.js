async function makeTransferPayments(a) {

    let retVal = confirm('Do you really want to proceed with payments');
    if(retVal === true){
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){
                dataArray.push($(selected[i]).val());
            }
        }

        $(a).text('Loading...').attr({'disabled':true});

        if(dataArray.length == 0){
            errorDisplay('Please select at least one withdrawal to continue');
            $(a).text('Make Payment').attr({'disabled':false});
            return;
        }

        let postData = await postRequest(RequestHandler.BaseUrl+"handle_transfers", {withdrawalId:dataArray.join('|')});

        if(postData.error_code == 0){
            $(a).text('Make Payment').attr({'disabled':false});
            successDisplay(postData.success_message);
            setTimeout(function () {
                location.reload();
            }, 5000);
            return;

        }
        handleTheErrorStatement(postData.error_message, 'off', 'no', 'yes');
    }

}

async function markAsPayed(a) {
    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading.....').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Confirm Withdrawals').attr({'disabled':false});
            errorDisplay('Please select at least one withdrawal to continue');
            return;
        }

        let postData = await theRequestHandler.postRequest("{{route('confirm_withdrawal_payment')}}", {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Confirm Withdrawals').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Confirm Withdrawals').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }

}