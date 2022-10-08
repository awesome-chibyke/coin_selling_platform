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
                <strong>${newCount})</strong>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="reward_type">{{ __('Nature of Reward') }}</label>
                    <select name="reward_type[]" id="reward_type" class="form-control @error('reward_type'+newCount) is-invalid @enderror">
                        <option value="">Select Nature of Reward</option>
                        <option value="cash">Reward for Investment Is In Cash</option>
                        <option selected value="kind">Reward for Investment Is In Kind</option>
                    </select>
                    @error('reward_type')
            <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
                                                </span>
                                                        @enderror
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="reward">{{ __('Description of Reward') }}</label>
                                                        <input type="text" id="reward_type${newCount}" name="reward[]" class="form-control @error('reward_type') is-invalid @enderror" required data-error="Description of Reward is required" placeholder="Description of Reward" value="{{ old('reward[]') }}"  />
                                                        @error('reward_type')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
            </div>
        </div>

        <div class="col-lg-6 col-md-6 hidden amount_holder">
            <div class="form-group">
                <label for="amount">{{ __('Amount') }}</label>
                                                        <input type="text" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" required data-error="Amount" placeholder="Amount" value="{{ old('reward') }}"  />
                                                        @error('amount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
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