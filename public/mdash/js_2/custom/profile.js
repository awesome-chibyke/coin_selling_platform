function promptProfileImageUpload() {

    $("#file_name").click();

}

async function UploadPlofileImage() {
    let retVal = confirm('Do you wish to upload selected image?');
    if(retVal === true){

        let formData = new FormData();
        formData.append("profile_image", document.getElementById('file_name').files[0]);

        let UploadImage = await theRequestHandler.postRequestData(RequestHandler.BaseUrl+'uploadUserImage', formData);

        UploadImage = JSON.parse(UploadImage);

        if(UploadImage.status === true){
            successDisplay(UploadImage.message);
            setTimeout(function () {
                location.reload();
            }, RequestHandler.TimeOutTime)
        }else{
            handleTheErrorStatement(UploadImage.message, showField = 'off', useClassForFieldFocus = 'no', useModal = 'no')
        }

    }
}