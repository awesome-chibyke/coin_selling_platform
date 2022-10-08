<!-- Trigger/Open The Modal -->
    <button style="display: none;" id="authenticateModalBtn">Open Modal</button>

    <!-- The Modal -->
    <div id="authenticateModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="close_modal">&times;</span>
            </div>
            <div class="modal-body">
                <div style="width:90%; margin-left:5%; margin-right:5%;">
                    <p class="text-warning text-center instruction-holder" style="margin-top:20px;" id="instruction-holder" hidden></p>

                    <div class="formPosition form-group" id="password_holder">
                        <label class="form-label" for="password">Amount (USD)</label>
                        <input placeholder="Enter Password" name="password" id="password"
                            pattern="\d*" myref="[object Object]"
                            autocomplete="off" type="password"
                            class="form-control">
                        <div class="err_password"></div>
                    </div>

                    <div class="form-group" style="margin-top:10px;">
                        <button id="submit_coin_payment_auth" style="background: #000000 none repeat scroll 0% 0%; border: medium none rgb(0, 0, 0);" type="button" class="form-control mb-4 btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script>

    const paymentProcessingButton = document.querySelector("#submit_coin_payment");

    paymentProcessingButton.addEventListener('click', function(){
        //if(confirm('Do you really want to continue?')){
           submitPaymentDetails();
        //}
    });

    const validatePaymentDatas = ({amount, coin_list, bank_list, account_number, account_name, phone_number, email}) => {
        //send the values to the API.
        let data = {amount, coin_list, bank_list, account_number, account_name, phone_number, email};

        let rules = {
            amount: 'required|numeric|min:3',
            coin_list: 'required|string',
            bank_list: 'required|min:3',
            account_number: 'required|min:3|numeric',
            account_name: 'required|string|min:3',
            phone_number: 'required|numeric|min:8',
            email: 'required|email|min:3',
        };

        let validation = new Validator(data, rules);
        return validation;
    }

    //pass the value of the coin list element to the
    const coin_list_element = document.querySelector("#coin_list");
    coin_list_element.addEventListener('change', async function(){
        const coin_list_value = coin_list_element.value;
        const url = `{{ URL::to('/') }}/get-coin-details/${coin_list_value}`
        const get_coin_details = await theGetRequest(url);
        const {status, message, data} = get_coin_details;
        if(status === true){
            document.querySelector('#coin_name').value = data.coin_market_data.name;
        }
    });

    const submitPaymentDetails = async () => {

        const amount = document.querySelector("#amount");
        const coin_list = document.querySelector("#coin_list");
        const bank_list = document.querySelector("#bank_list");
        const account_number = document.querySelector("#account_number");
        const account_name = document.querySelector("#account_name");
        const phone_number = document.querySelector("#phone_number");
        const email = document.querySelector("#email");
        const coin_name = document.querySelector('#coin_name');

        //validate the values
        const valuesToProcess = {amount:amount.value, coin_list:coin_list.value, bank_list:bank_list.value, account_number:account_number.value, account_name:account_name.value, phone_number:phone_number.value, email:email.value};
        const validation = validatePaymentDatas(valuesToProcess);

        if(validation.fails()){
            return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on');
        }

        const url = '{{ route('send-payment-no-auth') }}';
        const mainText = updateButtonStatus(paymentProcessingButton, 'get_main_text_and_set_loading');//update the status of the button to loading

        //send the payment and user details to the backend
        try{
            valuesToProcess.coin_name = coin_name.value;
            const getPaymentProperties = await thePostRequest(url, JSON.stringify(valuesToProcess));
            const {status, message, request_password, data} = getPaymentProperties;

            updateButtonStatus(paymentProcessingButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){

                if(request_password === true){
                    //display modal or payment
                    const instruction_holder = document.querySelector("#instruction-holder");
                    instruction_holder.innerHTML = message;
                    instruction_holder.removeAttribute('hidden');
                    openAuthModal('authenticateModal');
                    return;
                }

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
            updateButtonStatus(paymentProcessingButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }

    }

    //process the payment for authentication
    const submitCoinPaymentAuthButton = document.querySelector("#submit_coin_payment_auth");
    submitCoinPaymentAuthButton.addEventListener('click', function(){
        submitPaymentDetailsAuth();
    });

    const validatePaymentAuthDatas = ({amount, coin_list, bank_list, account_number, account_name, phone_number, email, password}) => {
        let data = {amount, coin_list, bank_list, account_number, account_name, phone_number, email, password};

        let rules = {
            amount: 'required|numeric|min:3',
            coin_list: 'required|string',
            bank_list: 'required|min:3',
            account_number: 'required|min:3|numeric',
            account_name: 'required|string|min:3',
            phone_number: 'required|numeric|min:8',
            email: 'required|email|min:3',
            password: 'required|min:3',
        };

        let validation = new Validator(data, rules);
        return validation;
    }

    const submitPaymentDetailsAuth = async () => {

        const amount = document.querySelector("#amount");
        const coin_list = document.querySelector("#coin_list");
        const bank_list = document.querySelector("#bank_list");
        const account_number = document.querySelector("#account_number");
        const account_name = document.querySelector("#account_name");
        const phone_number = document.querySelector("#phone_number");
        const email = document.querySelector("#email");
        const password = document.querySelector("#password");
        const coin_name = document.querySelector('#coin_name');

        //validate the values
        const valuesToProcess = {amount:amount.value, coin_list:coin_list.value, bank_list:bank_list.value, account_number:account_number.value, account_name:account_name.value, phone_number:phone_number.value, email:email.value, password:password.value};
        const validation = validatePaymentAuthDatas(valuesToProcess);

        if(validation.fails()){
            return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on');
        }

        const url = '{{ route('send-payment-authicate') }}';
        const mainText = updateButtonStatus(submitCoinPaymentAuthButton, 'get_main_text_and_set_loading');//update the status of the button to loading

        //send the payment and user details to the backend
        try{
            valuesToProcess.coin_name = coin_name.value;
            const getPaymentProperties = await thePostRequest(url, JSON.stringify(valuesToProcess));
            const {status, message, request_password, data} = getPaymentProperties;//send-payment-authicate

            updateButtonStatus(submitCoinPaymentAuthButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
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
            updateButtonStatus(submitCoinPaymentAuthButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }

    }

    function openAuthModal(modalId){
        //$('#authenticateModalBtn').click();
        // Get the modal
        var modal = document.getElementById(modalId);

        // Get the button that opens the modal
        //var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        //var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        //btn.onclick = function() {
        modal.style.display = "block";
        //}
    }

    const modalCloseButton = document.querySelector("#close_modal");
    modalCloseButton.addEventListener('click', function(){
        closeModal('authenticateModal');
    });

    function closeModal(modalId){
        var modal = document.getElementById(modalId);
        // When the user clicks on <span> (x), close the modal
        //span.onclick = function() {
            modal.style.display = "none";
        //}

        // When the user clicks anywhere outside of the modal, close it
        // window.onclick = function(event) {
        //     if (event.target == modal) {
        //         modal.style.display = "block";
        //     }
        // }
    }

    //amount_you_have btcText coin_list_calculator

    const coin_list_calculator = document.querySelector("#coin_list_calculator");
    const amount_you_have = document.querySelector("#amount_you_have");
    const btcText = document.querySelector("#btcText");

    coin_list_calculator.addEventListener('change', function(){
        if(amount_you_have.value === ''){
            return;
        }

        if(coin_list_calculator.value === ''){
            return;
        }

        if(isNaN(amount_you_have.value)){
            return;
        }
        calculateRate();
    });

    amount_you_have.addEventListener('input', function(){
        if(amount_you_have.value === ''){
            return;
        }

        if(coin_list_calculator.value === ''){
            return;
        }

        if(isNaN(amount_you_have.value)){
            return;
        }
        calculateRate();
    });

    const calculateRate = async () => {

        const coin_list_value = coin_list_calculator.value;
        const url = `{{ URL::to('/') }}/get-coin-details-two/${coin_list_value}/${amount_you_have.value}`
        const get_coin_details = await theGetRequest(url);
        const {status, message, data} = get_coin_details;
        if(status === true){
            const {estimated_amount, coin_market_data} = data;
            const {local_currency_rate, name} = coin_market_data;

            if(local_currency_rate !== null){
                const amountValue = formatMoney(estimated_amount * local_currency_rate);
                btcText.innerHTML = amountValue;
                return;
            }
        }
        btcText.innerHTML = formatMoney(0);

    }


    //track a transaction
    const transaction_id_button = document.querySelector("#transaction_id_button");
    transaction_id_button.addEventListener('click', async function(){

        try{

            const transaction_id = document.querySelector("#transaction_id");
            const transaction_id_value = transaction_id.value;

            if(transaction_id_value === ''){ return swal("Error!", 'Transaction ID is required', "error"); }

            const url = `{{ URL::to('/') }}/track-payment-async/${transaction_id_value}`
            const url2 = `{{ URL::to('/') }}/track-payment/${transaction_id_value}`
            const mainText = updateButtonStatus(transaction_id_button, 'get_main_text_and_set_loading');//update the status of the button to loading
            const get_coin_details = await theGetRequest(url);
            const {status, message, data} = get_coin_details;

            updateButtonStatus(transaction_id_button, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){
                swal("Success!", message, "success");
                setTimeout(() => {
                    window.location.href = url2;
                }, 4000);
            }

            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }

        }catch(e){
            updateButtonStatus(transaction_id_button, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }

    });

</script>


<!-- Trigger/Open The Modal -->
    <button style="display: none;" id="myBtn">Open Modal</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <!--<p class="modal-content">Some text in the Modal Body</p>-->
                <div style="width:80%; margin-left:10%; margin-right:10%; margin-top:20px; margin-bottom:20px;">
                    @php $imageName = $display_banner !== null ? asset('storage/'.$image_folder.$display_banner->filename) : null; @endphp
                <img src="{{ $imageName }}" width="100%">
                </div>
            </div>
        </div>

    </div>

@if($display_banner !== null)
<script>

    $( document ).ready(function() {

        const localStorageData = localStorage.getItem('loaded_banner');
        if(localStorageData === null){
            $('#myBtn').click();
            const timeNow = moment().format("YYYY-MM-DD hh:mm:ss");
            localStorage.setItem('loaded_banner', JSON.stringify({'time': timeNow}));
        }else{
            const objectValue = JSON.parse(localStorageData);
            const timeNow = moment().format("YYYY-MM-DD hh:mm:ss");
            const addADay = moment(objectValue.time).add(1, 'days').format("YYYY-MM-DD hh:mm:ss");
            if(addADay < timeNow){
                $('#myBtn').click();
                const timeNow = moment().format("YYYY-MM-DD hh:mm:ss");
                localStorage.setItem('loaded_banner', JSON.stringify({'time': timeNow}));
            }
        }

    });
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function(event) {
    //     if (event.target == modal) {
    //         modal.style.display = "none";
    //     }
    // }


</script>

@endif
