function addNewRewardField() {

    let reward_holders = $('.reward_holder');
    let TotalLength = reward_holders.length;
    let newCount = 0;
    for(let i = 0; i < TotalLength; i++){
        let lastValue = parseFloat(TotalLength)-parseFloat(1);
        if(parseFloat(i) == lastValue){
            let lastCount = $(reward_holders[i]).attr('reward_holder_count');
            newCount = parseFloat(lastCount) + 1;
        }
    }

    let field = `
        <div class="col-sm-12 reward_holder" reward_holder_count="${newCount}">
            <div class="row">

            <div class="col-lg-12 col-md-12">
                <strong style="color:white;">${newCount})</strong>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                <input type="hidden" name="reward_unique_id[]" class="form-control" value=""  />
                    <label for="reward">Reward</label>
                    <input type="text" id="reward${newCount}" name="reward[]" class="form-control " required data-error="Description of Reward is required" placeholder="Description of Reward"  />
                   
                </div>
            </div>

            <div  class="col-sm-12" style="margin-bottom: 20px;">
                    <button onclick="removeRewardField(this, '.reward_holder')" type="button" class="btn guoBtn" title="Add new fields for reward"><i class="fa fa-trash"></i></button>
            </div>

    </div>
</div>
`;

    //
    $(field).insertBefore('.reward_field_adder')//reward_holder_count reward_holder

}

function removeRewardField(a, selected) {
    $(a).closest(selected).remove();
}

async function deletePlans(a) {
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
            $(a).text('Delete Plan(s)').attr({'disabled':false});
            errorDisplay('Please select at least one withdrawal to continue');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'deletePlans', {dataArray:dataArray});
        if(postData.error_code == 0){
            $(a).text('Delete Plan(s)').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Delete Plan(s)').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }
}

$(document).ready(function () {
    showInvestDetails($("#investment_settings_id"), 'off');//call the function that selects investment based on invesment settings

});



async function showInvestDetails(a, errorSwitch = 'on') {
    let unique_id = $(a).val();
    //$(a).text('Loading....').attr({'disabled':true});
    if(unique_id === ''){
        $(".amount_2_holder").addClass('hidden');
        $('.hiddenDiv').addClass('hidden');
        if(errorSwitch === 'on'){
            errorDisplay('Please Select A Plan To Continue');
        }
        return;
    }

    let getFormDetails = await theRequestHandler.getRequest(RequestHandler.BaseUrl+'get_single_investment_settings/'+unique_id);
    let investmentSettingDetails = getFormDetails;
    let list_of_rewards = '';
    if(investmentSettingDetails.error_code == 0){
        let details = investmentSettingDetails.data;
        let Amount = Math.round(details.amount * details.loggedUserCurrency.rate_of_conversion);
        let amountForForm = Math.round(details.form_fee * details.loggedUserCurrency.rate_of_conversion);

        let duration_in_days = Math.round(details.duration_for_referral_reward);
        $("#amount_").val('('+details.loggedUserCurrency.second_currency+') '+formatMoney(Amount, 2));
        $("#amount_for_form").val('('+details.loggedUserCurrency.second_currency+') '+formatMoney(amountForForm, 2));
        $("#duration_in_days").val(duration_in_days+ ' Days');


        //get the rewards
        if(details.rewards_details.length > 0){
            list_of_rewards += '<h4 style="color:white;">List of Incentives for this Package</h4>'
            var rewardDetailss = details.rewards_details;
            for(var i in rewardDetailss){
                list_of_rewards+=`<ul>
                <li><span style="color:white;">* </span><span style="color:white;">${rewardDetailss[i].reward}</span></li>
            </ul>`

            }

            $(".list_of_rewards").html(list_of_rewards)
        }

        //duration amount_ amount_holder duration_holder
        $('.hiddenDiv').removeClass('hidden');
    }

    if(investmentSettingDetails.error_code == 1){
        $('.hiddenDiv').addClass('hidden');
        $('.amount_2_holder').addClass('hidden');
    }

}

async function ConfirmDisbursedIncentive(a) {

    let retVal = confirm('Do you wish to continue?');
    if(retVal === true){
        $(a).text('Loading....').attr({'disabled':true});
        let selected = $(".smallCheckBox2");
        let dataArray = [];
        for(let i = 0; i < selected.length; i++){
            if($(selected[i]).is(':checked')){

                dataArray.push($(selected[i]).val());
            }
        }

        if(dataArray.length == 0){
            $(a).text('Confirm Disbursed Incentive(s)').attr({'disabled':false});
            errorDisplay('Please select at least one incentive  to continue');
            return;
        }

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'confirm_disbursement', {dataArray:dataArray});
        if(postData.status == true){
            $(a).text('Confirm Disbursed Incentive(s)').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                location.reload();
            }, 5000)
        }else{
            $(a).text('Confirm Disbursed Incentive(s)').attr({'disabled':false});
            errorDisplay(postData.error_message);
        }
    }

}