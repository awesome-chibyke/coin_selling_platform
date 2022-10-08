<script>

    $(document).on('click', '.level_changer', async function(){

        if(confirm('Do you really want to continue?') === true){

            const clicked = $(this);
            let mainText = $(clicked).closest('.action_holder').siblings('.typeOfUserHolder').text();

            //send the data to the back end
            let url = $(clicked).closest('.action_holder').attr('data-url');

            const columnName = $(clicked).attr('data-column-name');
            const keyword = $(clicked).attr('data-keyword');

            try{

                const userLevelUpdate = await thePostRequest(url, JSON.stringify({
                    columnName,
                    keyword
                }));

                const {status, message} = userLevelUpdate;

                if(status === true){

                    $(clicked).closest('.action_holder').siblings('.typeOfUserHolder').html(mainText);
                    swal("Success!", message, "success");
                    setTimeout(function () {
                        location.reload();
                    }, 4000);

                }

                if(status === false){
                    $(clicked).closest('.action_holder').siblings('.typeOfUserHolder').html(mainText);
                    return validateModule.handleErrorStatement(message, '../login', 'on');
                    //swal.fire("ERROR!", addSignalRequest.message, "error");
                }

            }catch(e){
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }

        }

    })

</script>
