function addNewFaqsField(){

    let faqs_fields_holder = $('.faqs_fields_holder');
    let TotalLength = faqs_fields_holder.length;
    let newCount = 0;
    for(let i = 0; i < TotalLength; i++){
        let lastValue = parseFloat(TotalLength)-parseFloat(1);
        if(parseFloat(i) == lastValue){
            let lastCount = $(faqs_fields_holder[i]).attr('data-count-holder');
            newCount = parseFloat(lastCount) + parseFloat(1);
        }
    }


    let field = `
            <div class="col-12 faqs_fields_holder" style="padding-left: 15px; padding-right: 15px;" data-count-holder="${newCount}">
                <div class="row">
                    <div class="col-12">
                        <h5 style="color:white;">${newCount})</h5>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="title_">Question</label>
                            <input type="text" id="question" name="question[]" class="form-control" placeholder="Question"  />
                        </div>
                                                            
                        </div>
            
                        <div class="col-12">
                            <div class="form-group">
                                <label for="summernote1">Answer</label>
                                <textarea id="summernote1" class="form-control" style="height: auto !important;" name="answers[]" placeholder="Enter Answers To The Question Above Here"></textarea>
                            </div>
                        </div>
            
                        <div  class="col-sm-12" style="margin-bottom: 20px;">
                            <button onclick="removeRewardField(this, '.faqs_fields_holder')" type="button" class="btn guoBtn" title="Add new fields for reward"><i class="fa fa-trash"></i></button>
                    </div>
            
                    </div>
                </div>
            
            `
    $(field).insertBefore('.faqs_field_adder');

}

async function confirmDeleteFaqs(a) {

    let retVal = confirm('Do you really want to continue?');
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
            $(a).text('Delete Selected Faq(s)').attr({'disabled':false});
            errorDisplay('Please select at least one faqs to be deleted');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'confirm_faq_delete', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete Selected Faq(s)').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete Selected Faq(s)').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }

    }

}