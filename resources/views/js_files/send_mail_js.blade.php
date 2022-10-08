<script>
    const selectedMailReceiverType = document.querySelector("#mail_readers");

    selectedMailReceiverType.addEventListener('change', function(){

        if(selectedMailReceiverType.value === '{{$bulkMailModelInstance->sendToAllUsers}}'){
            document.querySelector("#user_table_holder").setAttribute('hidden', true);
        }

        if(selectedMailReceiverType.value === '{{$bulkMailModelInstance->sendToSelectedUsers}}'){
            document.querySelector("#user_table_holder").removeAttribute('hidden');
        }
    });

    const mailSendButton = document.querySelector("#send_mail");

    mailSendButton.addEventListener('click', function(){
        sendMail();
    });

    const returnSelectedUser = () => {
        let selectedUsers = document.querySelectorAll('.smallCheckBox');
        let arrayOfSelectedUsers = [];
        if(selectedUsers.length > 0){
            for(let i = 0; i < selectedUsers.length; i++){
                if(selectedUsers[i].checked){
                    arrayOfSelectedUsers.push(selectedUsers[i].value)
                }
            }
        }
        return arrayOfSelectedUsers;
    }

    const emptyTheFields = () => {
        document.querySelector("#title").value = "";
        document.querySelector("#mail_readers").value = "";
        document.querySelector("#mail_body").value = "";
        document.querySelector("#filename").value = "";
    }

    const processFormData = ({title, mail_readers, mail_body, selected_user_array}) => {
        let formData = new FormData();
        const filename = document.querySelector('#filename');
        if (document.getElementById('filename').files.length > 0) {

            for (let m = 0; m < filename.files.length; m++) {
                formData.append("filename[]", filename.files[m]);
            }
        }

        formData.append('title', title);
        formData.append('mail_readers', mail_readers);
        formData.append('mail_body', mail_body);
        formData.append('selected_user_array', selected_user_array);
        return formData;
    }

    function validateMailDatas({title, mail_readers, mail_body}){
        let formData = {title, mail_readers, mail_body};

        let rules = {
            title: 'required|string|min:3',
            mail_readers: 'required|string|min:3',
            mail_body: 'required|min:3|string'
        };

        let validation = new Validator(formData, rules);
        return validation;
    }

    const sendMail = async () => {
        const title = document.querySelector("#title");
        const mail_readers = document.querySelector("#mail_readers");
        const mail_body = document.querySelector("#mail_body");
        const filename = document.querySelector("#filename");
        let selectedUserArray = [];

        let validation = validateMailDatas({title:title.value, mail_readers:mail_readers.value, mail_body:mail_body.value});
        if(validation.fails()){ return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on'); }

        if(mail_readers === '{{$bulkMailModelInstance->sendToSelectedUsers}}'){
            selectedUserArray = returnSelectedUser();//check for selected users and loop through and build an array
            if(selectedUserArray.length == 0){
                return validateModule.handleErrorStatement({general_error:['Please select atleast one user']}, '../login', 'on');
            }
        }

        const formData = processFormData({title:title.value, mail_readers:mail_readers.value, mail_body:mail_body.value, selected_user_array:selectedUserArray});

        const url = "{{ route('send-message') }}";
        const mainText = updateButtonStatus(mailSendButton, 'get_main_text_and_set_loading');//update the status of the button to loading

        try{
            const sendMailDetails = await thePostRequestData(url,formData);
            const {status, message, data} = JSON.parse(sendMailDetails);

            updateButtonStatus(mailSendButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            if(status === true){
                swal("Success!", message, "success");
                setTimeout(() => {
                    location.reload();
                }, 4000);
            }
            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }
        }catch(e){
            updateButtonStatus(mailSendButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }
    }


</script>

