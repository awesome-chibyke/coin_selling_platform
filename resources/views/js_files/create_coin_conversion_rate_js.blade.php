<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="create_coin_conversion_modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="mySmallModalLabel">Create/Update Coin Rates ($)</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-12">
                <p class="text-warning text-center">You can update the coin conversion rate.</p>
            </div>
            <div class="form-group col-md-12 col-12">
                <label for="coin_name">Coin Name</label>
                <input type="text" id="coin_name" readonly name="coin_name" class="form-control" />
                <span class="text-danger err_coin_name"></span>
            </div>
            <div class="form-group col-md-12 col-12">
                <label for="coin_code">Coin Code</label>
                <input type="text" id="coin_code" readonly name="coin_code" class="form-control" />
                <span class="text-danger err_coin_code"></span>
            </div>
            <div class="form-group col-md-12 col-12">
                <label for="coin_code">Local Currency Rate</label>
                <input type="text" id="rate_in_local_currency" name="rate_in_local_currency" class="form-control rate_in_local_currency" />
                <span class="text-danger err_rate_in_local_currency"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-whitesmoke br">
        <input type="hidden" id="coinCodeHolder" />
        <input type="hidden" id="coinNameHolder" />
        <button type="button" class="btn btn-primary" id="create_coin_conversion_btn">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>

<script>

    var getAllCoins = document.querySelectorAll('.triggerCoinRateUpdateProcedure');
    for(let i = 0; i < getAllCoins.length; i++){
        let eachCoinHolder = getAllCoins[i];

        eachCoinHolder.addEventListener('click', function(){
            const coinCode = eachCoinHolder.getAttribute('data-coin-code');
            const coinName = eachCoinHolder.getAttribute('data-coin-name');
            const localRate = eachCoinHolder.getAttribute('data-local-rate');

            //call a modal that seeks for amount to be sent and also the
            document.querySelector("#coin_code").value = coinCode;
            document.querySelector("#coin_name").value = coinName;
            document.querySelector("#rate_in_local_currency").value = localRate;

            $('#create_coin_conversion_modal').modal({
                backdrop: 'static',
                keyboard: false
            })
        })
    }


    $(document).on('click', '#create_coin_conversion_btn', function(){

    })

    var processCoinRateCreation = document.querySelector('#create_coin_conversion_btn');
    processCoinRateCreation.addEventListener('click', function(){
        processCoinRateCreationAndUpdate();
    })

    function validateCoinData(coin_code, coin_name, rate_in_local_currency){
        let formData = {
            coin_code: coin_code,
            coin_name: coin_name,
            rate_in_local_currency: rate_in_local_currency
        };

        let rules = {
            coin_name: 'required|string|min:3',
            coin_code: 'required|string',
            rate_in_local_currency: 'required|min:3|numeric'
        };

        let validation = new Validator(formData, rules);
        return validation;
    }

    async function processCoinRateCreationAndUpdate(){
        //get the amounht, coinn code and name
        const coin_code = document.querySelector("#coin_code").value;
        const coin_name = document.querySelector("#coin_name").value;
        const rate_in_local_currency = document.querySelector("#rate_in_local_currency").value;

        let validation = validateCoinData(coin_code, coin_name, rate_in_local_currency);
        if(validation.fails()){ return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on'); }

        const url = '{{ route('store-update-payment') }}';
        const mainText = updateButtonStatus(processCoinRateCreation, 'get_main_text_and_set_loading');//update the status of the button to loading

        try{
            const getPaymentProperties = await thePostRequest(url, JSON.stringify({
            coin_code: coin_code,
            coin_name: coin_name,
            rate_in_local_currency: rate_in_local_currency
        }));
            const {status, message, data} = getPaymentProperties;

            updateButtonStatus(processCoinRateCreation, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){
                swal("Success!", message, "success");
                $('#create_coin_conversion_modal').modal('hide');
                //cal a function that will load the invoice on a modal
                updateCoinInterface(getAllCoins, data);
            }

            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }
        }catch(e){
            updateButtonStatus(processCoinRateCreation, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }
    }

    function updateCoinInterface(getAllCoins, data){
        var allCoinsHolder = document.querySelectorAll('.triggerCoinRateUpdateProcedure');
        for(let i = 0; i < allCoinsHolder.length; i++){
            let eachCoinHolder = allCoinsHolder[i];
            const coinCode = eachCoinHolder.getAttribute('data-coin-code');
            if(coinCode === data.unique_id){
                eachCoinHolder.setAttribute('data-local-rate', data.rate_in_local_currency);
                document.querySelector("#local_currency_rate"+data.unique_id).innerHTML = `<strong>Rate: </strong> ${data.local_currency}${data.rate_in_local_currency}/$`;
            }
        }
    }

</script>
