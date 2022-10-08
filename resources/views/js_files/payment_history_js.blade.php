<script>

    var deletePayments = document.querySelector('#deletePayments');
    deletePayments.addEventListener('click', function(){
        deleteSelectedPayments();
    })

    const returnSelectedPayments = () => {
        let selectedPayments = document.querySelectorAll('.smallCheckBox');
        let arrayOfSelectedPayments = [];
        if(selectedPayments.length > 0){
            for(let i = 0; i < selectedPayments.length; i++){
                if(selectedPayments[i].checked){
                    arrayOfSelectedPayments.push(selectedPayments[i].value)
                }
            }
        }
        return arrayOfSelectedPayments;
    }

    async function deleteSelectedPayments(){

        const arrayOfSelectedPayments = returnSelectedPayments();

        if(arrayOfSelectedPayments.length == 0){
            return validateModule.handleErrorStatement({general_error:['Please select atleast one payment to be deleted']}, '../login', 'on');
        }

        if(arrayOfSelectedPayments.length > 0){
            if(confirm('Do you really want to delete payment(s) ?') === false){ return }

            //send the payment to the payment delete reqquest backend
            const url = "{{ route('delete-payments') }}";
            const mainText = updateButtonStatus(deletePayments, 'get_main_text_and_set_loading');//update the status of the button to loading

            try{
                const getPaymentProperties = await thePostRequest(url, JSON.stringify({selected_payments:arrayOfSelectedPayments}));
                const {status, message, data} = getPaymentProperties;

                updateButtonStatus(deletePayments, 'set_main_text', mainText);//set the main tet of the button back to the maintext
                if(status === true){
                    swal("Success!", message, "success");
                    //cal a function that will load the invoice on a modal
                    setTimeout(() => {
                        location.reload();
                    }, 4000);
                }
                if(status === false){
                    validateModule.handleErrorStatement(message, '../login', 'on');
                }
            }catch(e){
                updateButtonStatus(deletePayments, 'set_main_text', mainText);//set the main tet of the button back to the maintext
                validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
            }
        }
    }
</script>
