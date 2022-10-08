function addNewVideoField() {
    let field = `

            <div class="col-sm-12">
                <div class="widget-text-box">
                    <div class="form-select-list">
                        <input type="text" name="video_url[]" class="form-control" placeholder="Video Url Example: https://example.com or https://wwww.example.com">
                        <div style="margin-bottom: 5px;">
                            <button onclick="removeRewardField(this, '.col-sm-12')" type="button" class="btn btn-danger" title="Add new fields for reward"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        `
    $(field).insertBefore('.video_field_adder');


}

function addNewVideoFieldForEdit(){
    let field = `

                    <div class="form-group">
                        <input type="text" name="video_url[]" class="form-control @error('description') is-invalid @enderror" placeholder="Video Url Example: https://example.com or https://wwww.example.com">
                        <div style="margin-bottom: 5px;">
                            <button onclick="removeRewardField(this, '.form-group')" type="button" class="btn btn-danger" title="Add new fields for reward"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
        `
    $(field).insertBefore('.video_field_adder');
}

async function confirmDeleteOfGalleryImages(a) {
    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading....').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Delete Selected Images').attr({'disabled':false});
            errorDisplay('Please select at least one image');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'deleteGalleryImage', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete Selected Images').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete Selected Images').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }
}

async function confirmGalleryDelete(a) {
    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading....').attr({'disabled':true});
        let selected = $(".smallCheckBox");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){
                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Delete Selected Galleries').attr({'disabled':false});
            errorDisplay('Please select at least one Gallery');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'deleteGallery', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete Selected Galleries').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete Selected Galleries').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }
}

function bringOutModalMain(selected) {
    $(selected).modal('show');
}