async function confirmDeleteTestimonies(a) {

    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading...').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Delete Selected Testimonies').attr({'disabled':false});
            errorDisplay('Please select at least one Testimony');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'confirm_testimony_delete', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete Selected Testimonies').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete Selected Testimonies').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }

}

async function approveTestimonies(a) {

    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading...').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Approve Selected Testimonies').attr({'disabled':false});
            errorDisplay('Please select at least one Testimony');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'approve_testimonies', {dataArray:dataArray});
        if(postData.error_code == 0){//approveTestimonies confirmDeleteTestimonies
            $(a).text('Approve Selected Testimonies').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Approve Selected Testimonies').attr({'disabled':false});
            handleTheErrorStatement(postData.error_statement);
        }
    }

}