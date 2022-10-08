async function accountManager(a, action) {

    let retVal = confirm('Please click the `OK` to continue');
    if(retVal === true){

        let userID = $(a).attr('data-user-holder');

        let manageAccount = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'manage_account', {action:action, userId:userID});

        if(manageAccount.error_code ==  0){
            successDisplay(manageAccount.success_message);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            errorDisplay(manageAccount.error_message);
        }

    }

}