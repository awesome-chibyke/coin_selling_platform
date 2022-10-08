function checkAll() {
    if($(".mainCheckBox").is(':checked')){
        $(".smallCheckBox").prop('checked', true);
    }else{
        $(".smallCheckBox").prop('checked', false);
    }
}

//show a modal
function bringOutModalMain(value) {
    //$(value).removeClass('hidden');
    $(value).modal('show');
}

//remove a selected field
function removeRewardField(a, selected){
    $(a).closest(selected).remove();
}

function handleTheErrorStatement(error_statement, showField = 'off', useClassForFieldFocus = 'no', useModal = 'no') {

    $(".error_carrier").text('');

    var counter = 0; let theKey = '';
    let errorStatementLenght = Object.keys(error_statement).length;
    for(var i in error_statement){
        if(counter == 0){ theKey = i; }

        if(typeof error_statement[i] === 'string'){
            if(error_statement[i].indexOf(':') != -1){
                error_statement[i] = error_statement[i].split(':');
            }
        }

        var txt = ''; let incomingErrorArray = [];
        for(var j in error_statement[i]){

            incomingErrorArray.push(error_statement[i][j]);

            txt += "<p style='font-size:12px' class='f-size error_carrier t-color'><span >*</span> "+error_statement[i][j]+"</p>";
        }

        if(i === 'general_error'){
            if(useModal === 'yes'){
                //callErrorModal(txt);
                $(".errorAreaHolder p").removeClass('t-color');
            }else{
                //returnFunctions.showSuccessToaster(txt, 'warning');
                errorDisplay(txt);
                $(".error_carrier").removeClass('t-color');
            }

        }else{
            $('.err_'+i).html(txt).removeClass('hidden');

            if(parseFloat(counter) == parseFloat(errorStatementLenght - 1)){
                if(showField === 'on'){
                    scrollIntoDomView(theKey, useClassForFieldFocus);
                    //returnFunctions.showSuccessToaster('Some validation errors occurred.', 'warning');
                }

            }
            counter++;
        }

    }

}

function scrollIntoDomView(selectedElement, useClassForFieldFocus = 'yes'){
    let prefix = '';
    let offset = '';
    if(useClassForFieldFocus === 'no'){
        offset = $("#"+selectedElement).offset(); // Contains .top and .left
        prefix = '#';
    }else{
        offset = $("."+selectedElement).offset(); // Contains .top and .left
        prefix = '.';
    }

    //Subtract 20 from top and left:

    offset.left -= 200;
    offset.top -= 200;
    //Now animate the scroll-top and scroll-left CSS properties of <body> and <html>:

    $('html, body').animate({
        scrollTop: offset.top,
        scrollLeft: offset.left
    });
    $(prefix+selectedElement).focus();

}

/*const successDisplay = (message) => swal(message, 'successful', 'success');
const errorDisplay = (message) => swal(message, 'Failed', 'error');*/

//const successDisplay = (message) => swal(message, 'successful', 'success');

const successNoty = (message) => swal(message, 'successful', 'success');

const failJson = ({ responseJSON: response }) => {
    errorDisplay(`${response.error.message}`)
};

//every thing copy
var clipboard = new ClipboardJS('.copybtn');

clipboard.on('success', function(e) {
    successDisplay('Copied!!!');

    /*e.clearSelection();*/
});

clipboard.on('error', function(e) {
    errorDisplay('Copy failed');
});

//generics
async function createTestimony(a) {

    let retVal = confirm('Click OK to continue?');
    if(retVal === true){
        let video_link = $("#video_link").val().trim();
        let testimony = $("#testimony").val().trim();
        let full_name = $("#full_name").val().trim();

        let postData = await theRequestHandler.postRequest(RequestHandler.BaseUrl+'store_testimony', {video_link:video_link, testimony:testimony, full_name:full_name});
        if(postData.error_code == 0){
            $(a).text('Submit').attr({'disabled':false});
            successDisplay(postData.success_statement);
            setTimeout(function () {
                $("#video_link").val('');
                $("#testimony").val('');
                $("#full_name").val('');
                location.reload();
            }, 5000)
        }else{
            $(a).text('Submit').attr({'disabled':false});
            handleTheErrorStatement(postData.error_statement);
        }
    }

}

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
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


//support update
$(document).ready(function () {
    supportNotifier();
})

async function supportNotifier() {

    //
    let postData = await theRequestHandler.getRequest(RequestHandler.BaseUrl+"get_support_notification");

    let {count} = postData;

    $(".notifier_class").html(`<i class="icon nalika-chat icon-wrap"></i> <span class="mini-click-non">Support <sup class="badge-info badge" style="color:white;">${count}</sup></span>`);

}