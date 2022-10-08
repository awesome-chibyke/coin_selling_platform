<script>
    const mainCheckBox = document.querySelector("#mainCheckBox");
    const allCheckBox = document.querySelectorAll(".smallCheckBox");

    mainCheckBox.addEventListener('click', function(){

        if(mainCheckBox.checked){

            allCheckBox.forEach(eachCheckbox => {
                eachCheckbox.checked = true;
            })
        }else{
            allCheckBox.forEach(eachCheckbox => {
                eachCheckbox.checked = false;
            })
        }
    })

    let TimeOutTime = 4000;//$("#MainBaseUrl").val().trim();

    async function assignRoles(a, option) {

        try {
            let retVal = confirm('Do you really want to assign selected roles to users');
            if(retVal === true){

                const userTypeId = document.querySelector("#typeOfUserIdHolder");

                const selected = document.querySelectorAll(".smallCheckBox");
                let dataArray = [];
                for(let i = 0; i < selected.length; i++){
                    if(selected[i].checked){
                        dataArray.push(selected[i].value);
                    }
                }

                if(dataArray.length == 0){
                    return alert('Please select at least one role to continue');
                }

                const url = "{{ route('store_role_for_user', [$user_type->unique_id])}}"
                const mainText = updateButtonStatus(a, 'get_main_text_and_set_loading');//update the status of the button to loading
                let postData = await thePostRequest(url, JSON.stringify({role_id:dataArray, option:option}));
                //await postRequest(mainBaseUrl+"/api/store_role_for_user/"+userTypeId, {role_id:dataArray, option:option});

                updateButtonStatus(a, 'set_main_text', mainText);//set the main tet of the button back to the maintext

                if(postData.status == true){
                    $(a).html(mainText).attr({'disabled':false});
                    alert(postData.message)
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                    return;

                }
                alert(postData.message);
            }
        }catch(e){
            updateButtonStatus(a, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }

    }

    //assign roles to the user types
    // $(document).on('click', '.assignRoles', function(){
    //     let action = $(this).attr('data-action');
    //     assignRoles($(this), action);
    // })

    const assignRolesButton = document.querySelectorAll(".assignRoles");
    assignRolesButton.forEach(eachButton => {
        eachButton.addEventListener('click', function(){
            const action = eachButton.getAttribute('data-action');
            assignRoles(eachButton, action);
        })
    });
</script>
