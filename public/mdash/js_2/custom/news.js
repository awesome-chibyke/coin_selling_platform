async function confirmDeleteNews(a) {
    let retVal = confirm('Do you wish to Delete the selected New(s)?');
    if(retVal === true){
        $(a).text('Loading').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Delete News').attr({'disabled':false});
            errorDisplay('Please select at least one news to continue');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'confirmNewsDelete', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete News').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete News').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }
}