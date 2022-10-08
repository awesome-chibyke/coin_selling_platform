
class RequestHandler {

    static BaseUrl = 'http://127.0.0.1:8000/';//$("#MainBaseUrl").val().trim();
    static TimeOutTime = 4000;//$("#MainBaseUrl").val().trim();

        getRequest(url){

        return new Promise(function (resolve, reject) {

            $.ajaxSetup({
                headers:{
                    'Source': "api",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.get(url, function (data, status) {

                if(status === 'success'){
                    resolve(data);
                }else{
                    //console.log(status)
                    reject(status);
                }
            }).fail(function(error) {//statusText: "Method Not Allowed"
                reject('A Network Error was encountered, message: ``'+error.statusText+'`` was returned. Please contact system administrator.');
            });

        });
    }

    postRequest(url, params){

        return new Promise(function (resolve, reject) {
//alert($('meta[name="csrf-token"]').attr('content'))
            $.ajaxSetup({
                headers:{
                    'Source': "api",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post(url, params, function (data, status, jqXHR) {
                if(status === 'success'){
                    resolve(data)
                }else{
                    reject(status)
                }
            }).fail(function(error) {//statusText: "Method Not Allowed"
                reject('A Network Error was encountered, message: ``'+error.statusText+'`` was returned. Please contact system administrator.')
            })


        })
    }

    displayNetWorkError(message) {
        returnFunctions.showSuccessToaster(message, 'warning');
        if($('.Gif-hold')){
            $('.Gif-hold').html(loading).parent('.gifHolder2').attr('hidden','hidden');
        }
        if($('.gifhold')){
            $('.gifhold').html(loader).parent('.gifHolder').addClass('hidden');
        }
    }

    postRequestData(url, params) {

        return new  Promise(function (resolve, reject) {

            $.ajaxSetup({
                headers:{
                    'Source': "api",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

                    returnFunctions.showSuccessToaster('A Network Error was encountered, Please contact system administrator', 'warning');
                    if($('.Gif-hold')){
                        $('.Gif-hold').html(loading).parent('.gifHolder2').attr('hidden','hidden');
                    }
                    if($('.gifhold')){
                        $('.gifhold').html(loader).parent('.gifHolder').addClass('hidden');
                    }

                }
            });

        })

    }

}

let theRequestHandler = new RequestHandler();