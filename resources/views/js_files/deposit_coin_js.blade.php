<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="amount_modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="mySmallModalLabel">Amount ($)</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-12">
                <p class="text-warning text-center">Please Insert Amount in Dollars, then click continue.</p>
            </div>
            <div class="form-group col-md-12 col-12">
                <label for="price_amount">Enter Amount ($)</label>
                <input type="text" id="price_amount" name="price_amount" class="form-control" />
                <span class="text-danger err_price_amount"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-whitesmoke br">
        <input type="hidden" id="coinCodeHolder" />
        <input type="hidden" id="coinNameHolder" />
        <button type="button" class="btn btn-primary" id="process_amount">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>

<script>
    // getAllH4.forEach(function(item, index) {

    // (function(item, i) { // creating closure
    //     item.addEventListener('click', function() {
    //         alert(item.textContent);
    //     });
    //      }(item, index))

    //  });

    var getAllCoins = document.querySelectorAll('.triggerPaymentProcedure');
    for(let i = 0; i < getAllCoins.length; i++){
        let eachCoinHolder = getAllCoins[i];

        eachCoinHolder.addEventListener('click', function(){
            const coinCode = eachCoinHolder.getAttribute('data-coin-code');
            const coinName = eachCoinHolder.getAttribute('data-coin-name');

            //call a modal that seeks for amount to be sent and also the
            document.querySelector("#coinCodeHolder").value = coinCode;
            document.querySelector("#coinNameHolder").value = coinName;

            $('#amount_modal').modal({
                backdrop: 'static',
                keyboard: false
            })
        })
    }

    var processAmount = document.querySelector('#process_amount');
    processAmount.addEventListener('click', function(){
        processPaymentAndReturnInvoice();
    })

    async function processPaymentAndReturnInvoice(){
        //get the amounht, coinn code and name
        const coin = document.querySelector("#coinCodeHolder").value;
        const coin_name = document.querySelector("#coinNameHolder").value;

        const price_amount = document.querySelector("#price_amount").value;

        //send the values to the API.
        let data = {
            coin: coin,
            coin_name: coin_name,
            price_amount: price_amount
        };

        let rules = {
            coin: 'required|string|min:3',
            coin_name: 'required|string',
            price_amount: 'required|min:2'
        };

        let validation = new Validator(data, rules);

        if(validation.fails()){
            return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on');
        }

        const url = '{{ route('initialize-payment') }}';
        const mainText = updateButtonStatus(processAmount, 'get_main_text_and_set_loading');//update the status of the button to loading

        try{
            const getPaymentProperties = await thePostRequest(url, JSON.stringify({coin:coin, price_amount:price_amount, coin_name:coin_name}));
            const {status, message, data} = getPaymentProperties;

            updateButtonStatus(processAmount, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){
                swal("Success!", message, "success");
                //cal a function that will load the invoice on a modal
                setTimeout(() => {
                    window.location.href = data.url;
                }, 4000);
            }
            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }
        }catch(e){
            updateButtonStatus(processAmount, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }
    }

</script>



