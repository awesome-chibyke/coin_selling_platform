
<script>

    var verify_account_button = document.querySelector('#verify_account_button');
    var account_number = document.querySelector('#account_number');
    var main_submit_button = document.querySelector('#main_submit_button');
    var beneficiary_name_holder = document.querySelector('#beneficiary_name_holder');
    var main_submit_button_holder = document.querySelector('#main_submit_button_holder');

    verify_account_button.addEventListener('click', function(){
        verifyAccountDetails();
    })

    async function verifyAccountDetails(){
        //get the amounht, coinn code and name
        const account_number = document.querySelector("#account_number").value;
        const bank_code = document.querySelector("#bank_code").value;
        const beneficiary_name = document.querySelector('#beneficiary_name');

        //send the values to the API.
        let data = {
            bank_code: bank_code,
            account_number: account_number,
        };

        let rules = {
            bank_code: 'required|string|min:3',
            account_number: 'required|string|min:10',
        };

        let validation = new Validator(data, rules);

        if(validation.fails()){
            return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on');
        }

        const url = '{{ route('verify-bank-account') }}';
        const mainText = updateButtonStatus(verify_account_button, 'get_main_text_and_set_loading');//update the status of the button to loading

        try{
            const bankVerificationDetails = await thePostRequest(url, JSON.stringify(data));
            const {status, message, data:returnedData} = bankVerificationDetails;

            updateButtonStatus(verify_account_button, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){
                swal("Success!", message, "success");
                //cal a function that will load the invoice on a modal
                beneficiary_name_holder.removeAttribute('hidden');
                beneficiary_name.value = returnedData.account_name;
                main_submit_button_holder.removeAttribute('hidden');
            }
            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }
        }catch(e){
            updateButtonStatus(verify_account_button, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }
    }

    account_number.addEventListener('input', function(){
        toggleButtonDisplay();
    });

    const toggleButtonDisplay = () => {
        if(account_number.value === ""){
            beneficiary_name_holder.setAttribute('hidden', true);
            main_submit_button_holder.setAttribute('hidden', true);
        }
    }
</script>;
