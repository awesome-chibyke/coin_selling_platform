<script>

    // headers: {
    //                 'Content-Type':'application/json',
    //                 'Accept':'application/json'
    //                 // 'Content-Type': 'application/x-www-form-urlencoded',
    //             }

    let baseUrl = "{{URL::to('/')}}/";
    let userUniqueId = "{{(auth()->user() == null)? '':auth()->user()->unique_id }}";

    function thePostRequest(url, params, headerValues = {'Content-Type':'application/json', 'Accept':'application/json'}){

        return new Promise(function (resolve, reject) {

            headerValues['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

            fetch(url, {
                method: 'POST', // *GET, POST, PUT, DELETE,
                //credentials: 'same-origin', // include, *same-origin, omit
                headers: headerValues,
                body: params // body data type must match "Content-Type" header
            })
            .then(response => response.json())
            .then(data => resolve(data) )
            .catch(error => reject(error.message));

        })
    }

    function dataTableShow(selectedTable = "#datatable-buttons"){
        let a=$(selectedTable).DataTable(
            {lengthChange:!1,
                buttons:["copy","print"],
                language:{
                    paginate:{
                        previous:"<i class='arrow_carrot-left'>",
                        next:"<i class='arrow_carrot-right'>"
                    }},
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }});

        $("#selection-datatable").DataTable(
            {select:{style:"multi"},
                language:{
                    paginate:{
                        previous:"<i class='arrow_carrot-left'>",
                        next:"<i class='arrow_carrot-right'>"}},
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),
            a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
    }

    function capitalizeFirstLetter(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function compareAdvertObjects(a, b) {
        const bandA = a.order;
        const bandB = b.order;

        let comparison = 0;
        if (bandA > bandB) {
            comparison = 1;
        } else if (bandA < bandB) {
            comparison = -1;
        }
        return comparison * 1;
    }

    function theGetRequest(url){

        return new Promise(function (resolve, reject) {

            fetch(url)
            .then((response) => response.json())
            .then((data) => resolve(data))
            .catch(error => reject(error.message));
        })
    }

    function displayNetWorkError(message) {
        swal.fire("ERROR!", message, "error");
    }

    function displaySuccessModal(message) {
        swal.fire("SUCCESS!", message, "success");
    }

    function convertCurrency(currentCurrency, amount){
        let currentRate;
        currentRate = currentCurrency;
        let USDAmount = amount * currentRate;
        let amounts = Math.round(USDAmount);
        return formatMoney(amounts);// I think this is the right algorithm :/
    }

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ","){
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    }


    function thePostRequestData(url, params) {

        return new Promise(function (resolve, reject) {

            $.ajaxSetup({
                headers:{
                    'Source': "api"
                }
            });

            $.ajax({
                url: url,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: params,
                type: 'post',
                success: function(response){
                    resolve(response);
                },
                error: function (XMLHttpRequest, textStatus, error) {

                    swal("ERROR!", 'A Network Error was encountered, Please contact system administrator', "error");
                    //reject(error);
                }
            });

        })

    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function checkAll() {
        if($(".mainCheckBox").is(':checked')){
            $(".smallCheckBox").prop('checked', true);
        }else{
            $(".smallCheckBox").prop('checked', false);
        }
    }
</script>
