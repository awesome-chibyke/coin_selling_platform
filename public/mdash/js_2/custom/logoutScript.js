async function logoutFuntion(){

    let RetVal = confirm('Do you wish to continue?');
    if(RetVal === true){
        let logOut = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'logUserOut', {});
        if(logOut.status === true){
            successDisplay('You have been logged out successfully');
            setTimeout(function () {
                window.location.href = RequestHandler.BaseUrl+'login';
            }, 3000)
        }
    }

}